<?php

class Column {
    private $tableId;
    private $name;
    private $type;
    private $isPK; // Is Primary Key
    private $references; // Array di Nomi delle colonne referenziate

    public function __construct($name, $type, $isPrimaryKey) {
        $this->name = $name;
        $this->type = $type;
        $this->isPK = $isPrimaryKey;
        $this->references = []; // Inizializzazione come array vuoto
    }

    // Getters
    public function getTableId() {
        return $this->tableId;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getIsPK() {
        return $this->isPK;
    }

    public function getReferences() {
        return $this->references;
    }

    // Setters
    public function setTableId($tableId) {
        $this->tableId = $tableId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setIsPK($isPK) {
        $this->isPK = $isPK;
    }

    public function setReferences($references) {
        $this->references = $references;
    }


    public function addReference( $columnName): void
    {
        $this->references[] = $columnName;
    }

    public function removeReference($columnName): bool
    {
        foreach ($this->references as $column) {
            if ($column === $columnName) {
                unset($this->references[$column]);
                // Reindex the array to maintain consistent indices
                $this->references = array_values($this->references);
                return true;
            }
        }
        return false;
    }

    public function insertOnDB(){
        global $con;
        $q = ""; //Query insert into Attributo
        $stmt = mysqli_prepare($con, $q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, 'ssi', $this->name, $this->type, $this->isPK);
        if (!mysqli_stmt_execute($stmt)) {
            die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

    }
}


?>

