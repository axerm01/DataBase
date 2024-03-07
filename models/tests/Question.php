<?php

class Question {

    protected $ID;
    protected $IDTest;

    public function getID() {
        return $this->ID;
    }

    public function getIDTest() {
        return $this->IDTest;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function setIDTest($IDTest) {
        $this->IDTest = $IDTest;
    }

    public function insertOnDB(){}
}


?>

