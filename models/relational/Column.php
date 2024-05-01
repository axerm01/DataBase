<?php
include_once('../../controllers/utils/connect.php');
class Column {
    public static function saveTableColumns($tableId, $name, $type, $isPK){
        global $con;
        $isPK = (int) $isPK;
        $q = "CALL CreateAttribute(?,?,?,?)";
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " .  $con->error);
        }
        $stmt->bind_param('issi', $tableId, $name, $type, $isPK );
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Attributo salvato: '.$name);
    }
    public static function getTableColumns($tableId)
    {
        global $con;
        $q = 'CALL ViewAllAttributes(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $tableId );
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'IDTabella' => $row['IDTabella'],
                'Nome' => $row['Nome'],
                'Tipo' => $row['Tipo'],
                'IsPK' => $row['IsPK'],
                'columns' => []
            ];
        }
        $stmt->close();
        return $data;
    }
}