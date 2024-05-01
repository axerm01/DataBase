<?php
include_once('../../controllers/utils/connect.php');
class Statistics {

    public static function classificaTestCompletati() {
        global $con;
        $stmt = $con->prepare("SELECT * FROM ClassificaTestCompletati");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $ranking = [];
        while ($row = $result->fetch_assoc()) {
            $ranking[] = $row;
        }
        $stmt->close();
        return $ranking;
    }

    public static function classificaRisposteCorrette() {
        global $con;
        $stmt = $con->prepare("SELECT * FROM ClassificaRisposteCorrette");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $ranking = [];
        while ($row = $result->fetch_assoc()) {
            $ranking[] = $row;
        }
        $stmt->close();
        return $ranking;
    }

    public static function classificaQuesiti() {
        global $con;
        $stmt = $con->prepare("SELECT * FROM ClassificaQuesiti");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $ranking = [];
        while ($row = $result->fetch_assoc()) {
            $ranking[] = $row;
        }
        $stmt->close();
        return $ranking;
    }
}