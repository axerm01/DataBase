<?php
include('../../controllers/utils/connect.php');

class CodeQuestion  {
    public static function getTestQuestions($testId) {
        global $con; // Assumi che $con sia la tua connessione al database

        $codiceQuery = $con->prepare("CALL ViewAllCodice(?)");
        $codiceQuery->bind_param('i', $testId);
        $codiceQuery->execute();
        $result = $codiceQuery->get_result();
        // Estrai i risultati
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
        $q = 'CALL CreateCodice(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iisss', $IDTest, $ID, $text, $sqlCode, $difficulty);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }
        log('Salvataggio della domanda di codice: '.$text);

        $stmt->close();
        return "Saved correctly";
    }

    public static function updateCodeQuestionText($IDTest, $ID, $text)
    {
        global $con;
        $q = 'CALL UpdateDescrizioneCodice(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis', $IDTest, $ID, $text);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        log('Aggiornamento del testo della domanda di codice: '.$text);
        return "Saved correctly";
    }

    public static function updateCodeQuestionSql($IDTest, $ID, $sql)
    {
        global $con;
        $q = 'CALL UpdateDescrizioneCodice(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis', $IDTest, $ID, $sql);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        log('Aggiornamento della query corretta della domanda di codice: '.$sql);
        return "Saved correctly";
    }

    public static function updateCodeQuestionDiff($IDTest, $ID, $diff)
    {
        global $con;
        $q = 'CALL UpdateDescrizioneCodice(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis', $IDTest, $ID, $diff);
        if (!$stmt->execute()) {
            return("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        log('Aggiornamento della difficoltà della domanda di codice '.$ID.' a: '.$diff);
        return "Saved correctly";
    }
    public static function deleteCodeQuestion($IDTest, $ID)
    {
        global $con;
        $q = 'CALL DropCodice(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii',$ID, $IDTest);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
    }

}