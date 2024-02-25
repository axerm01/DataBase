<?php

class Column {
    private $tableId;
    private $name;
    private $type;
    private $isPK; // Is Primary Key
    private $references; // Array di Nomi delle colonne referenziate

    public function __construct($tableId, $name, $type, $isPrimaryKey = false) {
        $this->tableId = $tableId;
        $this->name = $name;
        $this->type = $type;
        $this->isPrimaryKey = $isPrimaryKey;
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

    public function createColumnOnDB($table_id, $name, $type, $isPK){
        //Query per creaz tabella

    }
}


?>

