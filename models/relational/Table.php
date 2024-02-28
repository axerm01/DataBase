<?php
include('../controllers/connect.php');

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
        global $con;
        $q = ""; //Query insert into Tabella
        $stmt = mysqli_prepare($con, $q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, 'ssi', $this->name, $this->professorEmail, $this->numRows);
        if (!mysqli_stmt_execute($stmt)) {
            die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

        foreach ($this->columns as $column) {
            $column->insertOnDB();
        }
    }

    public function createNewTable()
    {
        global $con;
        $q = $this->createTableQuery(); //Query create table
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
        $columnDefinitions = [];
        foreach ($this->columns as $column) {
            $colDef = "`" . $column->name . "` " . $column->type;
            if ($column->isPK) {
                $colDef .= " PRIMARY KEY";
            }
            $columnDefinitions[] = $colDef;
        }

        $columnDefinitionsString = implode(", ", $columnDefinitions);
        $query = "CREATE TABLE `" . $this->name . "` (" . $columnDefinitionsString . ");";
        return $query;
    }

    public function fillTableRow($rows) {
        global $con;
        mysqli_begin_transaction($con);

        try {
            foreach ($rows as $rowData) {
                $valueList = [];
                foreach ($rowData as $index => $value) {
                    // Controlla il tipo di dato per ogni colonna e formatta il valore di conseguenza
                    $type = $this->columns[$index]->type;
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

