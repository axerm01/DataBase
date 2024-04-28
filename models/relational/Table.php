<?php
include_once('../../controllers/utils/connect.php');
include_once ('../../models/relational/Column.php');

class Table {

    public static function saveTableData($professorEmail, $name, $creationDate, $columns)
    {
        global $con;
        $q = 'CALL CreateTable(?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sss',  $professorEmail, $name, $creationDate);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $con->query("SELECT @lastID as lastID");
        $row = $result->fetch_assoc();
        $id = $row['lastID'];
        $stmt->close();

        foreach ($columns as $column) {
            Column::saveTableColumns($id, $column['name'], $column['type'], $column['PK']);
        }

        logMongo('Tabella salvata: '.$name);
        return $id;
    }

    public static function createNewTable($name, $columns) {
        global $con;

        if (empty($columns)) {
            return"La lista delle colonne è vuota.";
        }

        $columnDefinitions = [];
        $primaryKeys = [];
        foreach ($columns as $column) {
            $colDef = "`" . $column['name'] . "` " . $column['type'];
            if ($column['PK']) {
                $primaryKeys[] = "`" . $column['name'] . "`";
            }
            $columnDefinitions[] = $colDef;
        }

        // Se ci sono più colonne PK, creiamo una PK composta
        if (!empty($primaryKeys)) {
            $columnDefinitions[] = "PRIMARY KEY (" . implode(", ", $primaryKeys) . ")";
        }

        $columnDefinitionsString = implode(", ", $columnDefinitions);
        $q = "CREATE TABLE IF NOT EXISTS `" . $name . "` (" . $columnDefinitionsString . ") ENGINE = InnoDB;";

        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return "Errore nella preparazione della query: " . $con->error;
        }
        if (!$stmt->execute()) {
            return "Errore nell'esecuzione della query: " . $stmt->error;
        }
        $stmt->close();

        $triggerName = $name . "_update_numrows";
        $tableName = $name;
        $triggerSQL = "
            CREATE TRIGGER " . $triggerName . "
            AFTER INSERT ON " . $tableName . " 
            FOR EACH ROW
            BEGIN
            UPDATE tabella
            SET NumRighe = NumRighe + 1
            WHERE Nome = '" . $name . "';
            END;
            ";

        $response = "Tabella creata correttamente, ";
        if ($con->query($triggerSQL) === TRUE) {
            $response .= "Trigger creato correttamente";
        } else {
            $response .= "Errore nella crezione del trigger: " . $con->error;
        }
        logMongo('Tabella e Trigger creati: '.$name);
        return $response;
    }

    public static function fillTableRows($rows, $columns, $name) {
        global $con;
        mysqli_begin_transaction($con);

        try {
            foreach ($rows as $rowData) {
                $valueList = [];
                foreach ($rowData as $index => $value) {
                    // Controlla il tipo di dato per ogni colonna e formatta il valore di conseguenza
                    $type = $columns[$index]['type'];
                    if (strpos($type, 'smallint') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false || strpos($type, 'double') !== false) {
                        // Tipi numerici: assumiamo che il valore sia già un numero valido
                        $valueList[] = $value;
                    } else {
                        // Tipi non numerici: circonda il valore con virgolette singole e sanifica per evitare SQL Injection
                        $valueList[] = "'" . mysqli_real_escape_string($con, $value) . "'";
                    }
                }

                $q = "INSERT INTO " . $name . " VALUES(" . implode(", ", $valueList) . ")";
                mysqli_query($con, $q);
            }

            mysqli_commit($con);
            $response = "Tutti i valori sono stati inseriti. ";
        } catch (Exception $e) {
            mysqli_rollback($con);
            // Gestisci l'errore
            $response = "Errore: " . $e->getMessage();
        }
        logMongo('Righe aggiunte a tabella '.$name.' :'.json_encode($rows));
        return $response;
    }

    public static function updateTableRows($rows, $columns, $title) {
        global $con;
        // Prepara una stringa con i nomi delle colonne per la query SQL
        $columnsList = '`' . implode('`, `', $columns) . '`';
        $query = "INSERT INTO `$title` ($columnsList) VALUES ";

        // Array per tenere i placeholders per i valori (?, ?, ?), ecc.
        $placeholders = array_fill(0, count($columns), '?');
        $placeholdersString = '(' . implode(', ', $placeholders) . ')';

        // Costruisci la parte VALUES della query SQL
        $valuesArr = [];
        $insertValues = [];
        foreach ($rows as $row) {
            $valuesArr[] = $placeholdersString;
            $insertValues = array_merge($insertValues, $row);
        }
        $query .= implode(', ', $valuesArr);

        // Preparazione della query
        if ($stmt = $con->prepare($query)) {
            // Determina i tipi di dati per bind_param dinamicamente
            $types = '';
            foreach ($insertValues as $value) {
                if (is_numeric($value) && intval($value) == $value) {
                    $types .= 'i';  // Tipo intero
                } else {
                    $types .= 's';  // Tipo stringa
                }
            }

            // Associa i valori dinamicamente
            $stmt->bind_param($types, ...$insertValues);

            // Esecuzione della query
            if (!$stmt->execute()) {
                return "Errore nell'esecuzione della query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            return "Errore nella preparazione della query: " . $con->error;
        }
        $con->close();
        return 'Righe inserite con successo!';
    }

    public static function deleteTable($tableId) {
        $name = Table::getTableName($tableId);
        $result = 'deleted ok';
        global $con; // Assumi che $con sia la tua connessione al database
        $tableQuery = $con->prepare("CALL DropTable(?,?)");
        if ($tableQuery === false) {
            $result = "Errore nella preparazione della query: " . $con->error;
        }
        $tableQuery->bind_param('is', $tableId, $name);
        if (!$tableQuery->execute()) {
            $result = "Errore nell'esecuzione della query: " . $tableQuery->error;
        }
        $tableQuery->close();

        $tableQuery = $con->prepare("DROP TABLE IF EXISTS ".$name);
        if ($tableQuery === false) {
            $result = "Errore nella preparazione della query 2: " . $con->error;
        }
        if (!$tableQuery->execute()) {
            $result = "Errore nell'esecuzione della query 2: " . $tableQuery->error;
        }
        $tableQuery->close();

        return $result;
    }

    public static function getAllTables($profEmail) //restituisce id tabella e nome
    {
        global $con;
        $q = 'CALL ViewAllTables(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $profEmail);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $content = [];
        while ($row = $result->fetch_assoc()) {
            $content[] = [
                'IDTabella' => $row['ID'],
                'Nome' => $row['Nome']
            ];
        }
        $stmt->close();

        return $content;
    }

    public static function getTableName($tableID)
    {
        global $con;

        // Prima, ottieni il nome della tabella in base al suo ID
        $queryNomeTabella = "SELECT Nome FROM tabella WHERE ID = ?";
        $stmt1 = $con->prepare($queryNomeTabella);
        if ($stmt1 === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }

        $stmt1->bind_param('i', $tableID);
        if (!$stmt1->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt1->error);
        }

        $resultNomeTabella = $stmt1->get_result();
        if ($row = $resultNomeTabella->fetch_assoc()) {
            $tableName = $row['Nome'];
        } else {
            die("Nessuna tabella trovata con l'ID specificato");
        }
        $stmt1->close();
        return $tableName;
    }

    public static function getTableContent($tableID){
        global $con;
        $tableName = Table::getTableName($tableID);

        $q = "SELECT * FROM ".$tableName;
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Aggiunge ogni riga all'array $data
        }
        $stmt->close();

        return $data;
    }

    public static function getTableHeader($tableID) //restituisce i dati di Tabella
    {
        global $con;
        $response = "ok";
        $q = 'CALL ViewTabella(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            $response = "Errore nella preparazione della query: " . $con->error;
        }
        $stmt->bind_param('i', $tableID);
        if (!$stmt->execute()) {
            $response = "Errore nell'esecuzione della query: " . $stmt->error;
        }

        $result = $stmt->get_result();
        $content = [];
        while ($row = $result->fetch_assoc()) {
            $content[] = [
                'IDTabella' => $row['ID'],
                'Nome' => $row['Nome'],
                'MailProfessore' => $row['MailProfessore'],
                'DataCreazione' => $row['DataCreazione'],
                'NumRighe' => $row['NumRighe']
            ];
        }
        $stmt->close();

        return $content;
    }

    public static function getTestTables($testId) {
            global $con; // Assumi che $con sia la tua connessione al database

            $tableQuery = $con->prepare("CALL ViewAllTT(?)");
            $tableQuery->bind_param('i', $testId);
            $tableQuery->execute();
            $result = $tableQuery->get_result();
            $content = [];
            while ($row = $result->fetch_assoc()) {
                $content[] = [
                    'IDTabella' => $row['IDTabella'],
                    'IDTest' => $row['IDTest']
                ];
            }
            $tableQuery->close();
            return $content;
    }

    public static function checkIfNameExists($nome) {
        global $con; // Assumi che $con sia la tua connessione al database (mysqli)

        // Prepara la chiamata alla stored procedure
        $stmt = $con->prepare("CALL CheckIfNameExists(?, @result)");
        if ($stmt === false) {
            // Gestisci l'errore di preparazione della query
            throw new Exception("Errore nella preparazione della query: " . $con->error);
        }

        // Lega i parametri
        $stmt->bind_param('s', $nome);

        // Esegui la stored procedure
        if (!$stmt->execute()) {
            // Gestisci l'errore di esecuzione della query
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        // Recupera il risultato
        $result = $con->query("SELECT @result AS result")->fetch_assoc();

        $stmt->close();

        // Restituisce il risultato booleano
        return (bool) $result['result'];
    }




}