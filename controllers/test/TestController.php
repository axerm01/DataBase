<?php
session_start();
include('../utils/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET"){ // restituzione tabelle presenti
    global $con;
    $q = "CALL ViewAllTables(?)";
    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
    $stmt->bind_param('s',$_SESSION['email']);
    if (!$stmt->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $tableNames = [];
    while ($row = $result->fetch_assoc()) {
        $tableNames[] = $row['Nome'];
    }

    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($tableNames);
}




?>

