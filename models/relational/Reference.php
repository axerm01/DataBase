<?php
include('../../controllers/utils/connect.php');

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
        global $con; // Assumi che $con sia la tua connessione al database

        // Converti l'array di ID in una stringa per la query SQL
        $tableIdsString = implode(',', array_map('intval', $tableIds));

        // Prepara la query SQL
        $query = "SELECT * FROM Referenze WHERE IDTab1 IN ($tableIdsString) OR IDTab2 IN ($tableIdsString)";

        // Esegui la query
        $result = $con->query($query);

        // Verifica che la query sia stata eseguita correttamente
        if (!$result) {
            die("Errore nell'esecuzione della query: " . $con->error);
        }

        // Estrai i risultati
        $references = [];
        while ($row = $result->fetch_assoc()) {
            $references[] = $row;
        }

        return $references;
    }

}