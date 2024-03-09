<?php
session_start();

include '../utils/connect.php';

    // Verifica se il contenuto ricevuto è JSON
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if (($_SERVER["REQUEST_METHOD"] == "POST")&&($contentType === "application/json")) {
        // Ricevi il contenuto grezzo
        $content = trim(file_get_contents("php://input"));

        // Decodifica il JSON ricevuto
        $decodedData = json_decode($content, true);

        // Crea un nuovo oggetto Table
        $table = new Table($decodedData['title'], $_SESSION['email'], count($decodedData['rows']));

        // Aggiungi le colonne alla tabella
        foreach ($decodedData['attributes'] as $columnData) {
            $column = new Column($columnData['name'], $columnData['type'], $columnData['PK']);
            $table->addColumn($column);
        }

        $table->insertOnDB();
        $table->createNewTable();
        $table->fillTableRows($decodedData['rows']);


    }


?>

