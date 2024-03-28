<?php
include('../../controllers/utils/connect.php');

class Question {

/*    public static function getQuestion($id, $testId) {
        $data = CodeQuestion::getQuestion($id, $testId);

        if ($data != 0) {
            // Domanda trovata nella tabella codice
            return $data;
        } else {
            // Cerca nella tabella scelta_multipla
            $data = MultipleChoiceQuestion::getQuestion($id, $testId);
        }
        return $data; // Nessuna domanda trovata
    }*/

    public static function saveQuestion($contentType, $stdEmail) {
    $response = "Not Saved";

    if ($contentType === "application/json") {
        // Ricevi il contenuto grezzo
        $content = trim(file_get_contents("php://input"));

        // Decodifica il JSON ricevuto
        $decodedData = json_decode($content, true);

        $type = $decodedData['type'];
        $testId = $decodedData['testId'];
        $questionId = $decodedData['questionId'];
        $answer = $decodedData['answer'];
        $outcome = $decodedData['outcome'];

        global $con;
        if ($con->connect_error) {
            die("Connessione fallita: " . $con->connect_error);
        }

// Prepara la query di selezione per verificare l'esistenza della riga
        $checkQuery = "SELECT * FROM risposta_studente WHERE stdEmail = ? AND testId = ? AND questionId = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param('sii', $stdEmail, $testId, $questionId);
        $stmt->execute();
        $result = $stmt->get_result();

// Controlla se esiste già una riga
        if ($result->num_rows > 0) {
            // Esegue l'UPDATE
            $updateQuery = "UPDATE risposta_studente SET answer = ?, outcome = ? WHERE stdEmail = ? AND testId = ? AND questionId = ?";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param('sisii', $answer, $outcome, $stdEmail, $testId, $questionId);
            $updateStmt->execute();
            $response = "Updated";
        } else {
            // Esegue l'INSERT
            $insertQuery = "INSERT INTO risposta_studente (stdEmail, type, testId, questionId, answer, outcome) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param('ssiisi', $stdEmail, $type, $testId, $questionId, $answer, $outcome);
            $insertStmt->execute();
            $response = "Added";
        }

        $stmt->close();
        $con->close();
    }
    return $response;
}

    public static function updateQuestion($decodedData, $stdEmail) {
        $response = "Not Saved";

            $type = $decodedData['type'];
            $testId = $decodedData['testId'];
            $questionId = $decodedData['questionId'];
            $answer = $decodedData['answer'];
            $outcome = $decodedData['outcome'];

            global $con;
            if ($con->connect_error) {
                die("Connessione fallita: " . $con->connect_error);
            }

// Prepara la query di selezione per verificare l'esistenza della riga
            $checkQuery = "SELECT * FROM risposta_studente WHERE stdEmail = ? AND testId = ? AND questionId = ?";
            $stmt = $con->prepare($checkQuery);
            $stmt->bind_param('sii', $stdEmail, $testId, $questionId);
            $stmt->execute();
            $result = $stmt->get_result();

// Controlla se esiste già una riga
            if ($result->num_rows > 0) {
                // Esegue l'UPDATE
                $updateQuery = "UPDATE risposta_studente SET answer = ?, outcome = ? WHERE stdEmail = ? AND testId = ? AND questionId = ?";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->bind_param('sisii', $answer, $outcome, $stdEmail, $testId, $questionId);
                $updateStmt->execute();
                $response = "Updated";
            } else {
                // Esegue l'INSERT
                $insertQuery = "INSERT INTO risposta_studente (stdEmail, type, testId, questionId, answer, outcome) VALUES (?, ?, ?, ?, ?, ?)";
                $insertStmt = $con->prepare($insertQuery);
                $insertStmt->bind_param('ssiisi', $stdEmail, $type, $testId, $questionId, $answer, $outcome);
                $insertStmt->execute();
                $response = "Added";
            }

            $stmt->close();
            $con->close();

        return $response;
    }

}