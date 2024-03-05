<?php
session_start();

include '../../models/relational/Table.php';
include '../../models/relational/Column.php';
include '../utils/connect.php';

    // Verifica se il contenuto ricevuto è JSON
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $action = filter_input(INPUT_GET, 'action');

    $data = '';
    switch ($action) {
        case 'get_tables': // GET
            $data = $this->getAllTables();
            break;

        case 'get_table_columns': // GET
            $id = filter_input(INPUT_GET, 'tableId');
            $data = $this->getTableColumns($id);
            break;
    }

    header('Content-Type: application/json');  // Imposta l'header per il contenuto JSON
    echo json_encode($data);  // Converte l'array $data in JSON e lo invia
}

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

function getAllTables()
{
    global $con;
    $q = 'CALL ViewAllTables(?);';
    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
    $stmt->bind_param('s', $_SESSION['email']);
    if (!$stmt->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'IDTabella' => $row['IDTabella'],
            'Nome' => $row['Nome']
        ];
    }
    $stmt->close();

    return $data;

}

function getTableColumns($tableId)
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
        $data[] = $row;  // Aggiunge ogni riga all'array $data
    }
    $stmt->close();

    return $data;

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

