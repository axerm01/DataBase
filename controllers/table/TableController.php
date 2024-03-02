<?php
session_start();
// Da richiamare quando si termina l'inserimento di tutte le colonne della tabella

/*if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SESSION['table']))) {
    $table = unserialize($_SESSION['table']);

    //Query DB per creaz tabella

    unset($_SESSION['table']);
}*/

include('../models/relational/Table.php');
include('../models/relational/Column.php');

    // Verifica se il contenuto ricevuto è JSON
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        // Ricevi il contenuto grezzo
        $content = trim(file_get_contents("php://input"));

        // Decodifica il JSON ricevuto
        $decodedData = json_decode($content, true);

        // Crea un nuovo oggetto Table
        $table = new Table($decodedData['title'], $_SESSION['email'], count($decodedData['rows']));

        // Aggiungi le colonne alla tabella
        foreach ($decodedData['attributes'] as $columnData) {
            $column = new Column($columnData['attributeName'], $columnData['attributeType'], $columnData['isPK']);
            $table->addColumn($column);
        }
        header('Location: ../login.html');
        //
        $table->insertOnDB();
        $table->createNewTable();
        $table->fillTableRow($decodedData['rows']);


    }




?>

