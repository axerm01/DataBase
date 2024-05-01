<?php
include_once('../../controllers/utils/connect.php');

class Answer {

    public static function saveMCAnswersData($ID, $IDTest, $IDMC, $text, $isCorrect)
    {
        global $con;
        $q = 'CALL CreateAnswer(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiisi', $ID, $IDTest, $IDMC, $text, $isCorrect);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Salvataggio opzione '.$text.' della domanda a scelta multipla '.$IDMC);
        return "Saved correctly";
    }

    public static function updateMCAnswerData($ID, $IDTest, $IDMC, $text, $isCorrect)
    {
        global $con;
        $q = 'CALL UpdateAnswer(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiisi', $ID, $IDTest, $IDMC, $text, $isCorrect);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Aggiornamento opzione '.$text.' della domanda a scelta multipla '.$IDMC);

        return "Saved correctly";
    }

    public static function deleteMCAnswersData($IDTest, $IDMC)
    {
        global $con;
        $q = 'CALL DropAnswers(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$IDTest, $IDMC);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

    // non usata
    public static function deleteMCSingleAnswer($IDTest, $IDMC, $ID)
    {
        global $con;
        $q = 'CALL DropSingleAnswer(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iii',$IDTest, $IDMC, $ID);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

}