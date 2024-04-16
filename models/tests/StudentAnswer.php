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
        mysqli_begin_transaction($con);

        try {
            foreach ($answers as $answer) {
                if ($answer['type'] === 'mc') {
                    // Utilizza la stored procedure per le risposte a scelta multipla
                    $stmt = $con->prepare("CALL CreateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $testId, $answer['IDDomanda'], $answer['Response'], $answer['Esito']);
                    $response = 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
                    // Utilizza la stored procedure per le risposte di tipo codice
                    $stmt = $con->prepare("CALL CreateCodiceStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siisi', $email, $testId, $answer['IDDomanda'], $answer['Response'], $answer['Esito']);
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

}