<?php

class Message {
    private $title;
    private $text;
    private $date;
    private $IDTest;
    private $sender;
    private $receiver;

    public function __construct($title, $text, $date, $IDTest, $sender, $receiver) {
        $this->title = $title;
        $this->text = $text;
        $this->date = $date;
        $this->IDTest = $IDTest;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    // Getter per title
    public function getTitle() {
        return $this->title;
    }

    // Setter per title
    public function setTitle($title) {
        $this->title = $title;
    }

    // Getter per text
    public function getText() {
        return $this->text;
    }

    // Setter per text
    public function setText($text) {
        $this->text = $text;
    }

    // Getter per date
    public function getDate() {
        return $this->date;
    }

    // Setter per date
    public function setDate($date) {
        $this->date = $date;
    }

    // Getter per IDTest
    public function getIDTest() {
        return $this->IDTest;
    }

    // Setter per IDTest
    public function setIDTest($IDTest) {
        $this->IDTest = $IDTest;
    }

    public function getSender() {
        return $this->sender;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function getReceiver() {
        return $this->receiver;
    }

    public function setReceiver($receiver) {
        $this->receiver = $receiver;
    }
}


?>