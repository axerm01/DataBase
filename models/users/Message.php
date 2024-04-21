<?php
include_once('../../controllers/utils/connect.php');
class Message {

    public static function getProfMessages() {
        global $con; // Assumi che $con sia l'oggetto di connessione al database mysqli
        $stmt = $con->prepare("CALL ViewMessaggiDocente()");
        $stmt->execute();

        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }

    public static function getStudentMessages($testId) {
        global $con; // Assumi che $con sia l'oggetto di connessione al database mysqli
        $stmt = $con->prepare("CALL ViewMessaggiStudente(?)");
        $stmt->bind_param('i', $testId);
        $stmt->execute();

        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }

    public static function sendStudentMessage($testId, $student_email, $titolo, $testo, $data) {
        global $con; // Assumi che $con sia l'oggetto di connessione al database mysqli
        $stmt = $con->prepare("CALL CreateMessaggioStudente(?, ?, ?, ?, ?)");
        $stmt->bind_param('sssis', $titolo, $testo, $data, $testId, $student_email);

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function sendProfMessage($testId, $prof_email, $titolo, $testo, $data) {
        global $con; // Assumi che $con sia l'oggetto di connessione al database mysqli
        $response = "All ok";

        $stmt = $con->prepare("CALL CreateMessaggioDocente(?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $response = "Errore nella preparazione della query: " . $con->error;
        }
        $stmt->bind_param('sssis', $titolo, $testo, $data, $testId, $prof_email);
        if (!$stmt->execute()) {
            $response = "Errore nell'esecuzione della query: " . $stmt->error;
        }
        $stmt->close();
        return $response;
    }
}