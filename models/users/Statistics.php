<?php
include_once('../../controllers/utils/connect.php');
class Statistics {

    public static function classificaTestCompletati() {
        global $con;
        $stmt = $con->prepare("SELECT * FROM ClassificaTestCompletati");
        $stmt->execute();

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
        $stmt->execute();

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
        $stmt->execute();

        $result = $stmt->get_result();
        $ranking = [];
        while ($row = $result->fetch_assoc()) {
            $ranking[] = $row;
        }
        $stmt->close();
        return $ranking;
    }



}