<?php

class Answer {
    private $ID;
    private $text;

    public function __construct($ID, $text) {
        $this->ID = $ID;
        $this->text = $text;
    }

    public function getID() {
        return $this->ID;
    }

    public function getText() {
        return $this->text;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function setText($text) {
        $this->text = $text;
    }
}


?>