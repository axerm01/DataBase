<?php
include_once('../../controllers/utils/connect.php');

class CodeQuestion  {
    public static function getTestQuestions($testId) {
        global $con;
        $codiceQuery = $con->prepare("CALL ViewAllCodeQuestions(?)");
        if ($codiceQuery === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $codiceQuery->bind_param('i', $testId);
        if (!$codiceQuery->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $codiceQuery->error);
        }
        $result = $codiceQuery->get_result();
        $codiceData = [];
        while ($row = $result->fetch_assoc()) {
            $row['type'] = "code";
            $codiceData[] = $row;
        }
        $codiceQuery->close();
        return $codiceData;
    }

    public static function saveCodeQuestion($IDTest, $ID, $text, $sqlCode, $difficulty)
    {
        global $con;
        $q = 'CALL CreateCodeQuestion(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iisss', $IDTest, $ID, $text, $sqlCode, $difficulty);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }
        logMongo('Salvataggio della domanda di codice: '.$text);

        $stmt->close();
        return "Saved correctly";
    }

    public static function deleteCodeQuestion($IDTest, $ID)
    {
        global $con;
        $q = 'CALL DropCodeQuestion(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$ID, $IDTest);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
    }

}