<?php
include('../../controllers/utils/connect.php');

class Student {

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
        global $con;
        $q = 'CALL ViewStudente(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $student = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $student[] = $row;  // Aggiunge ogni riga all'array $student
        }
        $stmt->close();
        $con->close();

        return $student;
    }

}