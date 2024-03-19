<?php
include('../../controllers/utils/connect.php');

class Table {
    private $id;
    private $professorEmail;
    private $name;
    private $creationDate;
    private $numRows;
    private $columns; // Un array di oggetti Column

    public function __construct($name, $professorEmail, $numRows) {
        $this->name = $name;
        $this->professorEmail = $professorEmail;
        $this->numRows = $numRows;
        $this->columns = array();
    }

    public function insertOnDB()
    {

        $this->creationDate = date('Y-m-d H:i:s');
        global $con;
        $q = 'CALL CreateTable(?,?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sssi',  $this->professorEmail, $this->name, $this->creationDate, $this->numRows);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $con->query("SELECT @lastID as lastID");
        $row = $result->fetch_assoc();
        $this->id = $row['lastID'];
        $stmt->close();

        foreach ($this->columns as $column) {
            $column->setTableId($this->id);
            $column->insertOnDB();
        }
    }

    public function createNewTable()
    {
        global $con;
        $q = $this->createTableQuery(); //Query create table

            /*$js_code = 'console.log(' . json_encode($q, JSON_HEX_TAG) .
                ');';
            if (true) {
                $js_code = '<script>' . $js_code . '</script>';
            }
            echo $js_code;*/


        $stmt = mysqli_prepare($con, $q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . mysqli_error($con));
        }
        if (!mysqli_stmt_execute($stmt)) {
            die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    }

    public function createTableQuery() {
        if (empty($this->columns)) {
            throw new Exception("La lista delle colonne è vuota.");
        }

        $columnDefinitions = [];
        $primaryKeys = [];
        foreach ($this->columns as $column) {
            $colDef = "`" . $column->getName() . "` " . $column->getType();
            if ($column->getIsPK()) {
                $primaryKeys[] = "`" . $column->getName() . "`";
            }
            $columnDefinitions[] = $colDef;
        }

        // Se ci sono più colonne PK, creiamo una PK composta
        if (!empty($primaryKeys)) {
            $columnDefinitions[] = "PRIMARY KEY (" . implode(", ", $primaryKeys) . ")";
        }

        $columnDefinitionsString = implode(", ", $columnDefinitions);
        $query = "CREATE TABLE IF NOT EXISTS `" . $this->name . "` (" . $columnDefinitionsString . ") ENGINE = InnoDB;";
        return $query;
    }


    public function fillTableRows($rows) {
        global $con;
        mysqli_begin_transaction($con);

        try {
            foreach ($rows as $rowData) {
                $valueList = [];
                foreach ($rowData as $index => $value) {
                    // Controlla il tipo di dato per ogni colonna e formatta il valore di conseguenza
                    $type = $this->columns[$index]->getType();
                    if (strpos($type, 'smaillint') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false || strpos($type, 'double') !== false) {
                        // Tipi numerici: assumiamo che il valore sia già un numero valido
                        $valueList[] = $value;
                    } else {
                        // Tipi non numerici: circonda il valore con virgolette singole e sanifica per evitare SQL Injection
                        $valueList[] = "'" . mysqli_real_escape_string($con, $value) . "'";
                    }
                }

                $q = "INSERT INTO " . $this->name . " VALUES(" . implode(", ", $valueList) . ")";
                mysqli_query($con, $q);
            }

            mysqli_commit($con);
        } catch (Exception $e) {
            mysqli_rollback($con);
            // Gestisci l'errore
            echo "Errore: " . $e->getMessage();
        }
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
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'IDTabella' => $row['ID'],
                'Nome' => $row['Nome']
            ];
        }
        $stmt->close();

        return $data;

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

    // Getters e Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getProfessorEmail() {
        return $this->professorEmail;
    }

    public function setProfessorEmail($professorEmail) {
        $this->professorEmail = $professorEmail;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getNumRows() {
        return $this->numRows;
    }

    public function setNumRows($numRows) {
        $this->numRows = $numRows;
    }

    // Metodi per gestire la lista delle colonne
    public function addColumn(Column $column) {
        $this->columns[] = $column;
    }

    public function getColumnByName($columnName) {
        foreach ($this->columns as $column) {
            if ($column->getName() === $columnName) {
                return $column;
            }
        }
        return null; // Nessuna colonna trovata con questo nome
    }

    public function removeColumn($columnName) {
        foreach ($this->columns as $key => $column) {
            if ($column->getName() === $columnName) {
                unset($this->columns[$key]);
                return true;
            }
        }
        return false;
    }

}


?>

