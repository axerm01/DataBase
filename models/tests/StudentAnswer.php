<?php
include_once('../../controllers/utils/connect.php');

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
                $id++;
                if ($answer['type'] === 'mc') {
                    // Utilizza la stored procedure per le risposte a scelta multipla
                    $stmt = $con->prepare("CALL CreateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $testId, $answer['id'], $answer['answerId'], $esiti[$id]);
                    $response = 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
                    // Utilizza la stored procedure per le risposte di tipo codice
                    $stmt = $con->prepare("CALL CreateCodiceStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siisi', $email, $testId, $answer['id'], $answer['sqlCode'], $esiti[$id]);
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
            $response =  "Errore1: " . $e->getMessage();
            // Gestione ulteriore dell'errore
        }
        logMongo('Salvataggio risposte dello svolgimento del Test '.$testId.' da '.$email);
        return $response;
    }

    public static function updateStudentAnswers($answers, $testId, $email) {
        global $con; // Assumi che $con sia la tua connessione al database

        $esiti = StudentAnswer::calcolaEsitoRisposte($answers, $testId);
        if($esiti == 'Query non consentita'){
            return $esiti;
        }

        mysqli_begin_transaction($con);
        $response = '';

        try {
            foreach ($answers as $id => $answer) {
                $id++;
                if ($answer['type'] === 'mc') {
                    // Utilizza la stored procedure per le risposte a scelta multipla
                    $stmt = $con->prepare("CALL UpdateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $answer['id'], $testId, $answer['answerId'], $esiti[$id]);
                    $response .= 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
                    // Utilizza la stored procedure per le risposte di tipo codice
                    $stmt = $con->prepare("CALL UpdateCodiceStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siisi', $email,  $answer['id'], $testId, $answer['sqlCode'], $esiti[$id]);
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
            $response =  "Errore2: " . $e->getMessage();
            // Gestione ulteriore dell'errore
        }
        logMongo('Aggiornamento risposte dello svolgimento del Test '.$testId.' da '.$email);
        return $response;
    }

    public static function calcolaEsitoRisposte($answers, $testId) {
        global $con;
        $results = [];

        // Ottieni le risposte corrette usando una funzione dedicata
        $correctMCAnswers = self::getCorrectMCAnswers($testId, $con);
        $correctCodeAnswers = self::getCorrectCodeAnswers($testId, $con);

        // Confronta le risposte dell'utente con le risposte corrette
        foreach ($answers as $questionId => $userAnswer) {
            $questionId++;
            if ($userAnswer['type'] === 'mc') {
                // Gestione delle risposte a scelta multipla
                $results[$questionId] = isset($correctMCAnswers[$questionId]) &&
                    $userAnswer['answerId'] == $correctMCAnswers[$questionId];
            } elseif ($userAnswer['type'] === 'code') {
                // Gestione delle risposte di codice
                if (isset($correctCodeAnswers[$questionId]) && self::isSafeQuery($userAnswer['sqlCode'])) {
                    $results[$questionId] = self::compareSqlResults($userAnswer['sqlCode'], $correctCodeAnswers[$questionId], $con);
                } else {
                    $results[$questionId] = false;
                }
            }
        }

        return $results;
    }

    private static function getCorrectMCAnswers($testId, $con) {
        $stmt = $con->prepare("CALL getCorrectAnswers(?)");
        $stmt->bind_param("i", $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $correctAnswers = [];
        while ($row = $result->fetch_assoc()) {
            $correctAnswers[$row['IDScMult']] = $row['ID'];
        }
        $stmt->close();
        return $correctAnswers;
    }

    private static function getCorrectCodeAnswers($testId, $con) {
        $stmt = $con->prepare("CALL ViewSqlCodice(?)");
        $stmt->bind_param("i", $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $correctAnswers = [];
        while ($row = $result->fetch_assoc()) {
            $correctAnswers[$row['ID']] = $row['Output'];
        }
        $stmt->close();
        return $correctAnswers;
    }

    private static function isSafeQuery($query) {
        // Verifica la sicurezza della query (evita SQL Injection e comandi dannosi)
        $disallowed = ['INSERT', 'DROP', 'DELETE', 'UPDATE', 'ALTER', 'CREATE', 'GRANT'];
        foreach ($disallowed as $keyword) {
            if (stripos($query, $keyword) !== false) {
                return false;
            }
        }
        return true;
    }

    private static function compareSqlResults($userQuery, $correctQuery, $con) {
        // Esecuzione delle query in modo sicuro e confronto dei risultati
        $userData = self::executeQuery($userQuery, $con);
        $correctData = self::executeQuery($correctQuery, $con);
        return $userData == $correctData; // Confronta gli array dei risultati
    }

    private static function executeQuery($query, $con) {
        $result = $con->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

}