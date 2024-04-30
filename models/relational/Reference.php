<?php
include_once('../../controllers/utils/connect.php');

class Reference
{
    public static function saveReferenceData($tab1, $tab2, $att1,  $att2)
    {
        global $con;
        $q = 'CALL CreateReference(?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiss', $tab1, $tab2, $att1,  $att2);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        logMongo('Vincolo salvato: '.$tab1.$tab2.$att1.$att2);
        $stmt->close();
    }

    public static function deleteReferenceData($tab1, $tab2, $att1,  $att2)
    {
        global $con;
        $q = 'CALL DropReference(?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiss', $tab1, $tab2, $att1,  $att2);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
    }
    public static function getReferencesByTableIds($tableIds) {
        global $con;
        // Converte l'array di ID in una stringa per la query SQL
        $tableIdsString = implode(',', array_map('intval', $tableIds));

        $query = "SELECT * FROM Referenze WHERE IDT1 IN ($tableIdsString) AND IDT2 IN ($tableIdsString)";
        $result = $con->query($query);
        if (!$result) {
            return "Errore nell'esecuzione della query: " . $con->error;
        }
        $references = [];
        while ($row = $result->fetch_assoc()) {
            $references[] = $row;
        }
        return $references;
    }

}