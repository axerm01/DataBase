<?php

class ProfessorList {
    private $professors = [];

    public function addProfessor(Professor $professor) {
        foreach ($this->professors as $existingProfessor) {
            if ($existingProfessor->getEmail() === $professor->getEmail()) {
                // Già esiste un professore con questa email
                return false;
            }
        }
        $this->professors[] = $professor;
        return true;
    }

    public function removeProfessor($email) {
        foreach ($this->professors as $key => $professor) {
            if ($professor->getEmail() === $email) {
                unset($this->professors[$key]);
                return true;
            }
        }
        return false;
    }

    public function getProfessorByEmail($email) {
        foreach ($this->professors as $professor) {
            if ($professor->getEmail() === $email) {
                return $professor;
            }
        }
        return null; // Nessun professore trovato con questa email
    }
}
?>
