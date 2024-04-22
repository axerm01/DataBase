<?php
include('../../controllers/utils/connect.php');

class StudentAnswer
{
    public static function getTestCodeAnswers($testId, $stdEmail) {
        global $con; // Assumi che $con sia la tua connessione al database

        $codiceQuery = $con->prepare("CALL ViewStudentCodeAnswers(?,?)");
        $codiceQuery->bind_param('is', $testId, $stdEmail);
        $codiceQuery->execute();
        $result = $codiceQuery->get_result();
        $codiceData = $result->fetch_all(MYSQLI_ASSOC);
        $codiceQuery->close();

        return $codiceData;
    }

    public static function getTestMCAnswers($testId, $stdEmail) {
        global $con; // Assumi che $con sia la tua connessione al database

        $mcQuery = $con->prepare("CALL ViewStudentMCAnswers(?,?)");
        $mcQuery->bind_param('is', $testId, $stdEmail);
        $mcQuery->execute();
        $result = $mcQuery->get_result();
        $mcData = $result->fetch_all(MYSQLI_ASSOC);
        $mcQuery->close();

        return $mcData;
    }

    public static function saveStudentAnswers($answers, $testId, $email) {
        global $con; // Assumi che $con sia la tua connessione al database
        $response = 'saved ok';

        $esiti = StudentAnswer::calcolaEsitoRisposte($answers, $testId);
        if($esiti == 'Query non consentita'){
            return $esiti;
        }

        mysqli_begin_transaction($con);

        try {
            foreach ($answers as $id => $answer) {
                if ($answer['type'] === 'mc') {
                    // Utilizza la stored procedure per le risposte a scelta multipla
                    $stmt = $con->prepare("CALL CreateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $testId, $answer['IDDomanda'], $answer['Response'], $esiti[$id]);
                    $response = 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
                    // Utilizza la stored procedure per le risposte di tipo codice
                    $stmt = $con->prepare("CALL CreateCodiceStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siisi', $email, $testId, $answer['IDDomanda'], $answer['Response'], $esiti[$id]);
                    $response .= 'updated code, ';
                }

                if (!$stmt->execute()) {
                    $response =  "Errore nell'esecuzione della stored procedure: " . $stmt->error;
                }

                $stmt->close();
            }
            mysqli_commit($con);


        } catch (Exception $e) {
            mysqli_rollback($con);
            $response =  "Errore: " . $e->getMessage();
            // Gestione ulteriore dell'errore
        }
        return $response;
    }

    public static function updateStudentAnswers($answers, $testId, $email) {
        global $con; // Assumi che $con sia la tua connessione al database

        mysqli_begin_transaction($con);
        $response = '';

        try {
            foreach ($answers as $answer) {
                if ($answer['type'] === 'mc') {
                    // Utilizza la stored procedure per le risposte a scelta multipla
                    $stmt = $con->prepare("CALL UpdateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $answer['IDDomanda'], $testId, $answer['Response'], $answer['Esito']);
                    $response .= 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
                    // Utilizza la stored procedure per le risposte di tipo codice
                    $stmt = $con->prepare("CALL UpdateCodiceStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siisi', $email,  $answer['IDDomanda'], $testId, $answer['Response'], $answer['Esito']);
                    $response .= 'updated code, ';
                }
                else {
                    $response = 'not updated';
                }

                if (!$stmt->execute()) {
                    return "Errore nell'esecuzione della stored procedure: " . $stmt->error;
                }

                $stmt->close();
            }

            mysqli_commit($con);
        } catch (Exception $e) {
            mysqli_rollback($con);
            $response =  "Errore: " . $e->getMessage();
            // Gestione ulteriore dell'errore
        }
        return $response;
    }

    public static function calcolaEsitoRisposte($answers, $testId){
        global $con;
        $results = [];

        // Chiamata alla stored procedure per ottenere le risposte corrette
        $stmt = $con->prepare("CALL getCorrectAnswers(?)");
        $stmt->bind_param("i", $testId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Associa le risposte corrette in un array per un facile accesso
        $correctMCAnswers = [];
        while ($row = $result->fetch_assoc()) {
            $correctMCAnswers[$row['IDScMult']] = $row['ID'];
        }

        $stmt = $con->prepare("CALL ViewSqlCodice(?)");
        $stmt->bind_param("i", $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $correctCodeAnswers = [];
        while ($row = $result->fetch_assoc()) {
            $correctCodeAnswers[$row['ID']] = $row['Output'];
        }

        // Confronta le risposte dell'utente con le risposte corrette
        foreach ($answers as $questionId => $userAnswer) {
            if ($userAnswer['type'] === 'mc') {
                if (array_key_exists($questionId, $correctMCAnswers)) {
                    $results[$questionId] = ($userAnswer['Response'] == $correctMCAnswers[$questionId]);
                } else {
                    // Se non esiste una risposta corretta, considera la risposta come errata
                    $results[$questionId] = false;
                }
            } elseif ($userAnswer['type'] === 'code') {
                if (array_key_exists($questionId, $correctCodeAnswers)) {
                    $q = $userAnswer['Response'];
                    if (stripos($q, 'INSERT') !== false || stripos($q, 'DROP') !== false || stripos($q, 'DELETE') !== false || stripos($q, 'UPDATE') !== false) {
                        return 'Query non consentita';
                    }

                    $result1 = $con->query($q);
                    $results1 = [];
                    while ($row = $result1->fetch_assoc()) {
                        $results1[] = $row;
                    }

                    $result2 = $con->query($correctCodeAnswers[$questionId]);
                    $results2 = [];
                    while ($row = $result2->fetch_assoc()) {
                        $results2[] = $row;
                    }

                    $results[$questionId] = ($results1 === $results2);

                } else {
                    // Se non esiste una risposta corretta, considera la risposta come errata
                    $results[$questionId] = false;
                }
            }
        }

        $stmt->close();
        return $results;
    }

}