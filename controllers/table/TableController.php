<?php
session_start();

include '../utils/connect.php';
include '../../models/relational/Table.php';

$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method){
    case 'GET':
        if (isset($_GET['action'])){
            $endpoint = $_GET['action'];
            $data = [];
            switch ($endpoint) {
                case 'get_tables': // GET delle tabelle create da un docente, restituisce id tabella e nome
                    $data = Table::getAllTables($_SESSION['email']);
                    break;

                case 'get_table_columns': // GET delle colonne di una tabella indicata
                    $id = filter_input(INPUT_GET, 'tableId');
                    $data = Column::getTableColumns($id);
                    break;

                case 'get_full_table': // GET del contenuto della tabella
                    $id = filter_input(INPUT_GET, 'tableId');
                    $columns = Column::getTableColumns($id);
                    $content = Table::getTableContent($id);

                    $data = array_merge(array($columns), $content);
                    break;

                case 'check_name': // GET delle colonne di una tabella indicata
                    $name = filter_input(INPUT_GET, 'name');
                    $data = Table::checkIfNameExists($name);
                    //$data = $name;
                    break;
            }
            echo json_encode($data);  // Converte l'array $data in JSON e lo invia
        } else {
            echo json_encode("no action");
        }
        break;

    case 'POST': //Creazione di una nuova tabella
        if (isset($_POST['action'])){
            $action = $_POST['action'];
            switch ($action) {
                case 'save_table':
                    $data = $_POST['data'];
                    $response = saveTable($data);
                    echo json_encode($response);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;

    case 'PUT': //Update di una tabella dato il suo ID
        if (isset($_GET['action']) && isset($_GET['tableId'])){
            $action = $_GET['action'];
            $tableId = $_GET['tableId'];
            switch ($action) {
                case 'update_table':
                    $data = $_POST['data'];
                    $response = updateTable($data);
                    echo json_encode($response);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;
        
    case 'DELETE': //Delete di una tabella dato il suo ID
        if (isset($_GET['tableId'])){
            $tableId = $_GET['tableId'];
            Table::deleteTable($tableId);
            echo json_encode('deleted successfully');
        }
        break;
}

function saveTable($data) {
    $decodedData = json_decode($data, true);
    $response = "";

    $creationDate = date('Y-m-d H:i:s');
    $tableId = Table::saveTableData($_SESSION['email'], $decodedData['title'], $creationDate, count($decodedData['rows']), $decodedData['attributes']);
    $response .= $tableId;
    $response .= Table::createNewTable($decodedData['title'], $decodedData['attributes']);
    $response .= Table::fillTableRows($decodedData['rows'], $decodedData['attributes'], $decodedData['title']);

    return $response;
}
function updateTable($data) {
    $decodedData = json_decode($data, true);
    $response = "";

    //Da capire se ha senso tenerlo
    Table::updateTableData($decodedData['tableId'], count($decodedData['rows']), $decodedData['attributes']);
    $response .= Table::updateTableRows($decodedData['rows'], $decodedData['attributes'], $decodedData['title']);

    return $response;
}

