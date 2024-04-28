<?php
include_once('../../controllers/utils/connect.php');
class Column {
    public static function saveTableColumns($tableId, $name, $type, $isPK){
        global $con;
        $isPK = (int) $isPK;
        $q = "CALL CreateAttribute(?,?,?,?)";
        $stmt = mysqli_prepare($con, $q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, 'issi', $tableId, $name, $type, $isPK );
        if (!mysqli_stmt_execute($stmt)) {
            die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
        log('Attributo salvato: '.$name);
    }
    public static function getTableColumns($tableId)
    {
        global $con;
        $q = 'CALL ViewAllAttributes(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $tableId );
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
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