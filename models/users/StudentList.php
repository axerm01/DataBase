<?php
class StudentList {
    private $students = [];

    public function addStudent(Student $student) {
        foreach ($this->students as $existingStudent) {
            if ($existingStudent->getEmail() === $student->getEmail()) {
                // Già esiste uno studente con questa email
                return false;
            }
        }
        $this->students[] = $student;
        return true;
    }

    public function removeStudent($email) {
        foreach ($this->students as $key => $student) {
            if ($student->getEmail() === $email) {
                unset($this->students[$key]);
                return true;
            }
        }
        return false;
    }

    public function getStudentByEmail($email) {
        foreach ($this->students as $student) {
            if ($student->getEmail() === $email) {
                return $student;
            }
        }
        return null; // Nessuno studente trovato con questa email
    }
}

?>

