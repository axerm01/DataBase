<?php
session_start();

include '../../models/relational/Table.php';
include '../../models/relational/Column.php';
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

        foreach ($decodedData['references'] as $columnRef) {
            $reference = new Reference($columnRef['tab1'], $columnRef['tab2'], $columnRef['attr1'], $columnRef['attr2']);
            $this->addReference();
        }

        $table->insertOnDB();
        $table->createNewTable();
        $table->fillTableRows($decodedData['rows']);


    }


    function addReference($tab1, $att1, $tab2, $att2){
        global $con;
        $q = 'CALL CreateReference(?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iiss', $tab1, $tab2, $att1, $att2 );
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }


?>

