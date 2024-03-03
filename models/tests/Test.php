<?php

class Test {
    private $id;
    private $title;
    private $creationDate;
    private $showAnswers;
    private $professorEmail;
    private $photo;
    private $questions; // Lista di oggetti Question

    public function __construct($id, $title, $creationDate, $showAnswers, $professorEmail) {
        $this->id = $id;
        $this->title = $title;
        $this->creationDate = $creationDate;
        $this->showAnswers = $showAnswers;
        $this->professorEmail = $professorEmail;
        $this->questions = [];
    }



    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getShowAnswers() {
        return $this->showAnswers;
    }

    public function getProfessorEmail() {
        return $this->professorEmail;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getQuestions() {
        return $this->questions;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setShowAnswers($showAnswers) {
        $this->showAnswers = $showAnswers;
    }

    public function setProfessorEmail($professorEmail) {
        $this->professorEmail = $professorEmail;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }


    public function addQuestion(Question $question) {
        $this->questions[$question->getID()] = $question;
    }

    public function removeQuestion($questionID) {
        foreach ($this->questions as $key => $question) {
            if ($question->getID() === $questionID) {
                unset($this->questions[$key]);
                $this->questions = array_values($this->questions);
                return true;
            }
        }
        return false;
    }

    public function getQuestionByID($questionID) {
        foreach ($this->questions as $question) {
            if ($question->getID() === $questionID) {
                return $question;
            }
        }
        return null;
    }
}

?>

}