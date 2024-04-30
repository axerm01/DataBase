<?php
include_once ('Answer.php');

class MultipleChoiceQuestion {

    public static function getTestQuestions($testId) {
        global $con;
        $stmt = $con->prepare("CALL ViewAllSceltaMultipla(?)");
        $stmt->bind_param('i', $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $MCQs = [];

        while ($row = $result->fetch_assoc()) {
            $row['type'] = "mc";
            $MCQs[] = $row;
        }
        $stmt->close();

        foreach ($MCQs as $index => $mcq) {
            $con->begin_transaction();
            try {
                $IDTest = $mcq['IDTest'];
                $ID = $mcq['ID'];
                $stmt = $con->prepare("CALL ViewAnswers(?, ?)");
                $stmt->bind_param('ii', $IDTest, $ID);
                $stmt->execute();
                $answersResult = $stmt->get_result();

                while ($answerRow = $answersResult->fetch_assoc()) {
                    $MCQs[$index]['answers'][] = $answerRow;
                }
                $stmt->close();
                $con->commit();

            } catch (mysqli_sql_exception $e) {
                $con->rollback();
                throw $e;
            }
        }
        return $MCQs;
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
        logMongo('Salvataggio domanda a scelta multipla: '.$description);
        return "Saved correctly";
    }

    public static function updateMCText($IDTest,$ID, $description)
    {
        global $con;
        $q = 'CALL UpdateSceltaMultiplaDescrizione(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis',$IDTest,$ID, $description);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Aggiornamento testo della domanda a scelta multipla: '.$description);
        return "Saved correctly";
    }

    public static function updateMCDiff($IDTest,$ID, $diff)
    {
        global $con;
        $q = 'CALL UpdateSceltaMultiplaDifficolta(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis',$IDTest,$ID, $diff);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Aggiornamento difficoltà della domanda a scelta multipla '.$ID.' a '.$diff);
        return "Saved correctly";
    }
    public static function updateMCNumAnswers($IDTest,$ID, $numAnswDifferential)
    {
        global $con;
        $q = 'CALL UpdateSceltaMultiplaNumeroRisposte(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iii',$IDTest,$ID, $numAnswDifferential);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Aggiornamento numero risposte della domanda a scelta multipla '.$ID);
        return "Saved correctly";
    }
    public static function updateMCAnswers($IDTest,$IDMC, $answers)
    {
        foreach ($answers as $answer) {
            Answer::updateMCAnswerData($answer['id'],$IDTest,$IDMC,$answer['text'],$answer['isCorrect'] );
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

}