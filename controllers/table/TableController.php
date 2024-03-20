<?php
session_start();

include '../utils/connect.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$endpoint = $uri[0];

echo "Risposta ricevuta ".$endpoint;

header('Content-Type: application/json');

switch ($method){
    case 'GET':
        //get all tables
        if($endpoint === '/tables'){

            //logica per gestire la get

        }
        // get table by ID
        else if(preg_match('/^\/tables\/(\d+)$/', $endpoint, $matches)){
            $tableId = $matches[1];

            //logica per gestire la get
        }

        break;

    case 'POST': //Creazione di una nuova tabella

        if($endpoint === '/tables'){
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

        break;

    case 'PUT': //Update di una tabella dato il suo ID
        if(preg_match('/^\/tables\/(\d+)$/', $endpoint, $matches)){
            $tableId = $matches[1];

            //logica per gestire la put
        }

        break;
    case 'DELETE': //Delete di una tabella dato il suo ID
        if(preg_match('/^\/tables\/(\d+)$/', $endpoint, $matches)){
            $tableId = $matches[1];

            //logica per gestire la delete
        }

        break;
}

?>

