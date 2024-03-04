<?php

class Answer {
    private $ID;
    private $IDMC;
    private $IDTest;
    private $text;
    private $isCorrect;

    public function __construct($ID, $IDMC, $text, $isCorrect) {
        $this->ID = $ID;
        $this->IDMC = $IDMC;
        $this->text = $text;
        $this->isCorrect = (int) $isCorrect;
    }

    public function insertOnDB()
    {
        global $con;
        $q = 'CALL CreateScelta(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('siiii', $this->text, $this->ID, $this->IDTest, $this->IDMC, $this->isCorrect);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

    public function getID() {
        return $this->ID;
    }

    public function getIDMC() {
        return $this->IDMC;
    }

    public function getIDTest() {
        return $this->IDTest;
    }

    public function getText() {
        return $this->text;
    }

    public function getIsCorrect() {
        return $this->isCorrect;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }
    public function setIDMC($IDMC) {
        $this->IDMC = $IDMC;
    }
    public function setIDTest($IDTest) {
        $this->IDTest = $IDTest;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setIsCorrect($correct) {
        $this->isCorrect = $correct;
    }
}


?>