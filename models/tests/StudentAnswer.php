<?php
include_once('../../controllers/utils/connect.php');

class StudentAnswer
{
    public static function getTestCodeAnswers($testId, $stdEmail) {
        global $con;

        $codiceQuery = $con->prepare("CALL ViewStudentCodeAnswers(?,?)");
        $codiceQuery->bind_param('is', $testId, $stdEmail);
        $codiceQuery->execute();
        $result = $codiceQuery->get_result();
        $codiceData = $result->fetch_all(MYSQLI_ASSOC);
        $codiceQuery->close();

        return $codiceData;
    }

    public static function getTestMCAnswers($testId, $stdEmail) {
        global $con;

        $mcQuery = $con->prepare("CALL ViewStudentMCAnswers(?,?)");
        $mcQuery->bind_param('is', $testId, $stdEmail);
        $mcQuery->execute();
        $result = $mcQuery->get_result();
        $mcData = $result->fetch_all(MYSQLI_ASSOC);
        $mcQuery->close();

        return $mcData;
    }

    public static function saveStudentAnswers($answers, $testId, $email) {
        global $con;
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
                    $stmt = $con->prepare("CALL CreateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $testId, $answer['id'], $answer['answerId'], $esiti[$id]);
                    $response = 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
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
        }
        logMongo('Salvataggio risposte dello svolgimento del Test '.$testId.' da '.$email);
        return $response;
    }

    public static function updateStudentAnswers($answers, $testId, $email) {
        global $con;
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
                    $stmt = $con->prepare("CALL UpdateRispostaStudente(?, ?, ?, ?, ?)");
                    $stmt->bind_param('siiii', $email, $answer['id'], $testId, $answer['answerId'], $esiti[$id]);
                    $response .= 'updated mc, ';
                } elseif ($answer['type'] === 'code') {
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
        }
        logMongo('Aggiornamento risposte dello svolgimento del Test '.$testId.' da '.$email);
        return $response;
    }

    public static function calcolaEsitoRisposte($answers, $testId) {
        global $con;
        $results = [];
        $correctMCAnswers = self::getCorrectMCAnswers($testId, $con);
        $correctCodeAnswers = self::getCorrectCodeAnswers($testId, $con);

        foreach ($answers as $questionId => $userAnswer) {
            $questionId++;
            if ($userAnswer['type'] === 'mc') {
                $results[$questionId] = isset($correctMCAnswers[$userAnswer['id']]) &&
                    $userAnswer['answerId'] == $correctMCAnswers[$userAnswer['id']];
            } elseif ($userAnswer['type'] === 'code') {
                if (isset($correctCodeAnswers[$userAnswer['id']]) && self::isSafeQuery($userAnswer['sqlCode'])) {
                    $results[$questionId] = self::compareSqlResults($userAnswer['sqlCode'], $correctCodeAnswers[$userAnswer['id']], $con);
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
        $disallowed = ['INSERT', 'DROP', 'DELETE', 'UPDATE', 'ALTER', 'CREATE', 'GRANT'];
        foreach ($disallowed as $keyword) {
            if (stripos($query, $keyword) !== false) {
                return false;
            }
        }
        return true;
    }

    private static function compareSqlResults($userQuery, $correctQuery, $con) {
        $userData = self::executeQuery($userQuery, $con);
        $correctData = self::executeQuery($correctQuery, $con);
        return $userData == $correctData;
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