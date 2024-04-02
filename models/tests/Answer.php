<?php
include('../../controllers/utils/connect.php');

class Answer {

    public static function saveMCAnswersData($ID, $IDTest, $IDMC, $text, $isCorrect)
    {
        global $con;
        $q = 'CALL CreateAnswer(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiisi', $ID, $IDTest, $IDMC, $text, $isCorrect);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

    public static function deleteMCAnswersData($IDTest, $IDMC)
    {
        global $con;
        $q = 'CALL DropAnswers(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$IDTest, $IDMC);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

    public static function getTestMCAnswers($testId) {
        global $con; // Assumi che $con sia la tua connessione al database

        $mcAnsQuery = $con->prepare("CALL ViewAllAnswers(?)");
        $mcAnsQuery->bind_param('i', $testId);
        $mcAnsQuery->execute();
        $result = $mcAnsQuery->get_result();
        $mcAnsData = $result->fetch_all(MYSQLI_ASSOC);
        $mcAnsQuery->close();

        return $mcAnsData;
    }

}