<?php

class TableList {

    private $tables = [];

    public function addTable(Table $table) {
        foreach ($this->table as $existingTable) {
            if ($existingTable->getId() === $table->getId()) {
                // Già esiste un table con questo ID
                return false;
            }
        }
        $this->tables[$table->getId()] = $table;
        return true;
    }

    public function removeTable($id) {
        foreach ($this->tables as $key => $table) {
            if ($table->getId() === $id) {
                unset($this->tables[$key]);
                return true;
            }
        }
        return false;
    }

    public function getTableById($id) {
        foreach ($this->tables as $table) {
            if ($table->getId() === $id) {
                return $table;
            }
        }
        return null; // Nessun table trovato con questo ID
    }

}