<?php
include ('Answer.php');

class MultipleChoiceQuestion {

    public static function getTestQuestions($testId) {
        global $con; // Assumi che $con sia la tua connessione al database

        $mcQuery = $con->prepare("CALL ViewAllSceltaMultipla(?)");
        $mcQuery->bind_param('i', $testId);
        $mcQuery->execute();
        $result = $mcQuery->get_result();
        $mcData = $result->fetch_all(MYSQLI_ASSOC);
        $mcQuery->close();

        return $mcData;
    }

    public static function saveMCData($IDTest,$ID, $description, $numAnswers, $difficulty, $answers)
    {
        global $con;
        $q = 'CALL CreateSceltaMultipla(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iisis',$IDTest,$ID, $description, $numAnswers, $difficulty);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();

        foreach ($answers as $answer) {
            Answer::saveMCAnswersData($answer['id'],$IDTest,$ID,$answer['text'],$answer['isCorrect'] );
        }

        return "Saved correctly";
    }

    public static function deleteMCData($IDTest,$IDMC)
    {
        global $con;
        $q = 'CALL DropSceltaMultipla(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$IDTest, $IDMC);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        Answer::deleteMCAnswersData($IDTest,$IDMC);
    }

    public static function getQuestion($id, $testId) {
        global $con; // Assicurati che $con sia la tua connessione al database

        $query = "SELECT * FROM scelta_multipla WHERE ID = ? AND IDTest = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $id, $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;  // Aggiunge ogni riga all'array $data
            }
        }
        else {
            return 0;
        }
        $stmt->close();

        $query = "SELECT * FROM scelta WHERE IDScMult = ? AND IDTest = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $id, $testId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;  // Aggiunge ogni riga all'array $data
            }
        }

        return $data;
    } // Da verificare se corretto

}