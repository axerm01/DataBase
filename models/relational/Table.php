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

