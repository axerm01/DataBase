<?php

class Student {
    private $firstName;
    private $lastName;
    private $email;
    private $registrationNumber;
    private $enrollmentYear;
    private $phoneNumber;
    private $sentMessages;
    private $receivedMessages;
    private $studentTestList;


    // Constructor
    public function __construct($firstName, $lastName, $email, $registrationNumber, $enrollmentYear, $phoneNumber) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->registrationNumber = $registrationNumber;
        $this->enrollmentYear = $enrollmentYear;
        $this->phoneNumber = $phoneNumber;
        $this->sentMessages = new MessageList();
        $this->receivedMessages = new MessageList();
        $this->studentTestList = [];
    }

    // Getters and Setters
    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getRegistrationNumber() {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber($registrationNumber) {
        $this->registrationNumber = $registrationNumber;
    }

    public function getEnrollmentYear() {
        return $this->enrollmentYear;
    }

    public function setEnrollmentYear($enrollmentYear) {
        $this->enrollmentYear = $enrollmentYear;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function getStudentTestList() {
        return $this->studentTestList;
    }


    // Gestione dei messaggi
    public function sendMesssage(Message $message) {
        $this->sentMessages->addMessage($message);
    }

    public function receiveMessage(Message $message) {
        $this->receivedMessages->addMessage($message);
    }

    public function getSentMessages() {
        return $this->sentMessages->getMessages();
    }

    public function getReceivedMessages() {
        return $this->receivedMessages->getMessages();
    }

    //Gstione dei test dello studente
    // Metodo per aggiungere un StudentTest alla lista
    public function addStudentTest(StudentTest $studentTest) {
        $this->studentTestList[$studentTest->getTestID()] = $studentTest;
    }

    // Metodo per rimuovere uno StudentTest dalla lista
    public function removeStudentTest($testID) {
        foreach ($this->studentTestList as $key => $studentTest) {
            if ($studentTest->getTestID() === $testID) {
                unset($this->studentTestList[$key]);
                $this->studentTestList = array_values($this->studentTestList); // Reindirizza gli indici
                return true;
            }
        }
        return false;
    }

    // Metodo per ottenere uno StudentTest dalla lista
    public function getStudentTest($testID) {
        foreach ($this->studentTestList as $studentTest) {
            if ($studentTest->getTestID() === $testID) {
                return $studentTest;
            }
        }
        return null;
    }

    public static function getStudent($email)
    {
        $student = '';
        //Query per select Student
        return $student;
    }

}


?>

