<?php
include('../../controllers/utils/connect.php');

class Question {

    protected $ID;
    protected $IDTest;
    protected $text;

    public function __construct($text) {
        $this->text = $text;
    }

    public static function getQuestion($id, $testId) {
        $data = CodeQuestion::getQuestion($id, $testId);

        if ($data != null) {
            // Domanda trovata nella tabella codice
            return $data;
        } else {
            // Cerca nella tabella scelta_multipla
            $data = MultipleChoiceQuestion::getQuestion($id, $testId);
        }
        return $data; // Nessuna domanda trovata
    }


    public function getID() {
        return $this->ID;
    }

    public function getIDTest() {
        return $this->IDTest;
    }

    public function getText() {
        return $this->text;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function setIDTest($IDTest) {
        $this->IDTest = $IDTest;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function insertOnDB(){}
}


?>

