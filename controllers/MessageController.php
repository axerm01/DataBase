<?php
class MessageController {
    private $students;
    private $professors;
    private $tests;

    public function __construct($studentList, $professorList, $testList) {
        $this->students = $studentList;
        $this->professors = $professorList;
        $this->tests = $testList;
    }

    public function findProfessorEmailByTestID($IDTest){
        return $this->tests->getTestById($IDTest)->getProfessorEmail();
    }

    public function sendMessageToProfessor($title, $text, $date, $IDTest, $studentEmail) {
        $professorEmail = $this->findProfessorEmailByTestID($IDTest);
        $message = new Message($title, $text, $date, $IDTest, $studentEmail, $professorEmail);
        $this->professors->getProfessorByEmail($professorEmail)->receiveMessage($message);
    }

    public function sendBroadcastMessageFromProfessor($title, $text, $date, $IDTest, $professorEmail) {
        foreach ($this->students->getStudents() as $studentEmail => $student) {
            $message = new Message($title, $text, $date, $IDTest, $professorEmail, $studentEmail);
            $student->receiveMessage($message);
        }
    }

}

?>