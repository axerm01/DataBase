<?php
include('../../controllers/utils/connect.php');
include ('../../models/relational/Column.php');

class Table {

    public static function saveTableData($professorEmail, $name, $creationDate, $numRows, $columns)
    {
        global $con;
        $q = 'CALL CreateTable(?,?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sssi',  $professorEmail, $name, $creationDate, $numRows);
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

        return $id;
    }

    //da verificare se basta un trigger per fare update di numRows
    public static function updateTableData($tableId, $numRows, $columns)
    {
        global $con;
        $q = 'CALL UpdateTable(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$tableId, $numRows);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
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
            die("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();

        return "Table created ";
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
                    if (strpos($type, 'smaillint') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false || strpos($type, 'double') !== false) {
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
            $response = " Filled OK";
        } catch (Exception $e) {
            mysqli_rollback($con);
            // Gestisci l'errore
            $response = "Errore: " . $e->getMessage();
        }

        return $response;
    }

    public static function updateTableRows($rows, $columns, $name) {
        global $con;
        mysqli_begin_transaction($con);

        try {
            foreach ($rows as $rowData) {
                $updatePairs = [];
                $whereConditions = [];

                foreach ($columns as $index => $column) {
                    $columnName = $column['nome']; // Assumiamo che 'name' sia il nome della colonna
                    $value = $rowData[$index];

                    if ($column['IsPK']) {
                        // Prepara le condizioni per la chiave primaria
                        $whereConditions[] = "$columnName = '" . mysqli_real_escape_string($con, $value) . "'";
                    } else {
                        // Prepara i campi da aggiornare
                        $type = $column['type'];
                        $valueFormatted = (strpos($type, 'smallint') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false || strpos($type, 'double') !== false) ? $value : "'" . mysqli_real_escape_string($con, $value) . "'";
                        $updatePairs[] = "$columnName = $valueFormatted";
                    }
                }

                $q = "UPDATE " . $name . " SET " . implode(", ", $updatePairs) . " WHERE " . implode(" AND ", $whereConditions);
                if (!mysqli_query($con, $q)) {
                    throw new Exception("Errore nell'update della riga: " . mysqli_error($con));
                }
            }

            mysqli_commit($con);
            $response = "Update OK";
        } catch (Exception $e) {
            mysqli_rollback($con);
            $response = "Errore: " . $e->getMessage();
        }

        return $response;
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

    public static function getTableContent($tableID){
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

    public static function getTestTables($testId) {
            global $con; // Assumi che $con sia la tua connessione al database

            $tableQuery = $con->prepare("CALL ViewAllTT(?)");
            $tableQuery->bind_param('i', $testId);
            $tableQuery->execute();
            $result = $tableQuery->get_result();
        $tableData = [];

        while ($row = $result->fetch_assoc()) {
            $tableData[] = [
                'ID' => $row['ID'],
                'MailProfessore' => $row['MailProfessore'],
                'Nome' => $row['Nome'],
                'DataCreazione' => $row['DataCreazione'],
                'NumRighe' => $row['NumRighe']
            ];
        }
        $tableQuery->close();
        return $tableData;

    }

    public static function deleteTable($tableId) {
        global $con; // Assumi che $con sia la tua connessione al database
        $tableQuery = $con->prepare("CALL DropTabella(?)");
        $tableQuery->bind_param('i', $tableId);
        $tableQuery->execute();
        $tableQuery->close();
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