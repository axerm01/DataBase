<?php

class Student {
    private $firstName;
    private $lastName;
    private $email;
    private $registrationNumber;
    private $enrollmentYear;
    private $phoneNumber;

    // Constructor
    public function __construct($firstName, $lastName, $email, $registrationNumber, $enrollmentYear, $phoneNumber) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->registrationNumber = $registrationNumber;
        $this->enrollmentYear = $enrollmentYear;
        $this->phoneNumber = $phoneNumber;
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
}


?>

