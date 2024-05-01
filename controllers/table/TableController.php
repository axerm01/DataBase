<?php
session_start();

include_once '../utils/connect.php';
include_once '../../models/relational/Table.php';

$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method){
    case 'GET':
        if (isset($_GET['action'])){
            $endpoint = $_GET['action'];
            $data = [];
            try {
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

                    case 'check_name':
                        $name = filter_input(INPUT_GET, 'name');
                        $data = Table::checkIfNameExists($name);
                        break;
                }
            }
            catch (Exception $e){
                $data = "Errore: ".$e->getMessage();
            }
            echo json_encode($data);

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
        if (isset($_GET['action']) && isset($_GET['title'])){
            $action = $_GET['action'];
            $title = $_GET['title'];
            switch ($action) {
                case 'update_table':
                    $rawData = file_get_contents('php://input');
                    $data = json_decode($rawData, true);
                    $response = updateTable($data['data'], $title);
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
            try {
                $response = Table::deleteTable($tableId);
            }
            catch (Exception $e){
                $response = "Errore durante l'eliminazione della tabella: " . $e->getMessage();
            }
            echo json_encode($response);
        }
        break;
}

function saveTable($data) {
    $decodedData = json_decode($data, true);
    $response = "ID Tabella: ";

    $creationDate = date('Y-m-d H:i:s');
    try {
        $tableId = Table::saveTableData($_SESSION['email'], $decodedData['title'], $creationDate, $decodedData['attributes']);
        $response .= $tableId.' - ';
        $response .= Table::createNewTable($decodedData['title'], $decodedData['attributes']);
        $response .= Table::fillTableRows($decodedData['rows'], $decodedData['attributes'], $decodedData['title']);
    } catch (Exception $e) {
        $response = "Errore durante il salvataggio della tabella: " . $e->getMessage();
    }
    return $response;
}
function updateTable($data, $title) {
    $attributes = array_keys($data[0]);
    // Preparazione delle righe
    $rows = array_map(function($item) {
        return array_values($item);
    }, $data);
    try {
        $response = Table::updateTableRows($rows, $attributes, $title);
    } catch (Exception $e) {
        $response = "Errore durante l'update della tabella: " . $e->getMessage();
    }
    return $response;
}

