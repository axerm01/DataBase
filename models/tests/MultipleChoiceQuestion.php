<?php

class MultipleChoiceQuestion extends Question {
    private $description;
    private $difficulty;
    private $numAnswers;
    private $answers; // Lista di oggetti Answer


    public function __construct($IDTest, $ID, $description, $difficulty, $numAnswers) {
        parent::__construct($ID, $IDTest);
        $this->description = $description;
        $this->difficulty = $difficulty;
        $this->numAnswers = $numAnswers;
        $this->answers = [];
    }

    // Getter e setter per description, difficulty, numAnswers

    public function getDescription() {
        return $this->description;
    }

    public function getDifficulty() {
        return $this->difficulty;
    }

    public function getNumAnswers() {
        return $this->numAnswers;
    }

    public function getAnswers() {
        return $this->answers;
    }


    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    public function setNumAnswers($numAnswers) {
        $this->numAnswers = $numAnswers;
    }

    public function setAnswers($answers) {
        $this->answers = $answers;
    }


    public function addAnswer(Answer $answer) {
        $this->answers[] = $answer;
    }

    public function removeAnswer($answerID) {
        foreach ($this->answers as $key => $answer) {
            if ($answer->getID() == $answerID) {
                unset($this->answers[$key]);
                $this->answers = array_values($this->answers); // Reindirizza l'array per mantenere l'ordine
                return true;
            }
        }
        return false;
    }

    public function getAnswerByID($ID) {
        foreach ($this->answers as $answer) {
            if ($answer->getID() === $ID) {
                return $answer;
            }
        }
        return null; // Nessuna risposta trovata con l'ID specificato
    }
}


?>

