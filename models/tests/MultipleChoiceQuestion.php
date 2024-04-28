<?php
include_once ('Answer.php');

class MultipleChoiceQuestion {

    public static function getTestQuestions($testId) {
        global $con; // Assumi che $con sia la tua connessione al database

        // Prima, recupera tutte le domande a scelta multipla per un dato testId
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

        // Ora, per ogni MCQ, richiama ViewAnswers e aggiungi le risposte agli MCQs
        foreach ($MCQs as $index => $mcq) {
            $con->begin_transaction();  // Inizia una transazione

            try {
                $IDTest = $mcq['IDTest'];
                $ID = $mcq['ID'];
                $stmt = $con->prepare("CALL ViewAnswers(?, ?)");
                $stmt->bind_param('ii', $IDTest, $ID);
                $stmt->execute();
                $answersResult = $stmt->get_result();

                // Aggiungi le risposte all'elemento MCQ corrispondente
                while ($answerRow = $answersResult->fetch_assoc()) {
                    $MCQs[$index]['answers'][] = $answerRow;
                }

                $stmt->close();
                $con->commit();  // Completa la transazione
            } catch (mysqli_sql_exception $e) {
                $con->rollback();  // Annulla la transazione in caso di errore
                throw $e;  // Rilancia l'eccezione per gestirla ulteriormente
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
        log('Salvataggio domanda a scelta multipla: '.$description);
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
        log('Aggiornamento testo della domanda a scelta multipla: '.$description);
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
        log('Aggiornamento difficoltà della domanda a scelta multipla '.$ID.' a '.$diff);
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
        log('Aggiornamento numero risposte della domanda a scelta multipla '.$ID);
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