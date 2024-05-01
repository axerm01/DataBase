<?php
include_once('../../controllers/utils/connect.php');
class Message {

    public static function getProfMessages($testId) {
        global $con;
        $stmt = $con->prepare("CALL ViewProfessorMessages(?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }

    public static function getStudentMessages($testId) {
        global $con;
        $stmt = $con->prepare("CALL ViewStudentMessages(?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }

    public static function sendStudentMessage($testId, $student_email, $titolo, $testo, $data) {
        global $con;
        $response = "All ok";
        $stmt = $con->prepare("CALL CreateStudentMessage(?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $response = "Errore nella preparazione della query: " . $con->error;
        }
        $stmt->bind_param('sssis', $titolo, $testo, $data, $testId, $student_email);
        if (!$stmt->execute()) {
            $response = "Errore nell'esecuzione della query: " . $stmt->error;
        }
        $stmt->close();
        logMongo('Inserimento di un messaggio dello studente '.$student_email.' : '.$titolo);
        return $response;
    }

    public static function sendProfMessage($testId, $prof_email, $titolo, $testo, $data) {
        global $con;
        $response = "All ok";

        $stmt = $con->prepare("CALL CreateProfessorMessage(?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $response = "Errore nella preparazione della query: " . $con->error;
        }
        $stmt->bind_param('sssis', $titolo, $testo, $data, $testId, $prof_email);
        if (!$stmt->execute()) {
            $response = "Errore nell'esecuzione della query: " . $stmt->error;
        }
        $stmt->close();
        logMongo('Inserimento di un messaggio del docente '.$prof_email.' : '.$titolo);
        return $response;
    }
}