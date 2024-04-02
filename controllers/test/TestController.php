<?php
session_start();
include('../utils/connect.php');
include '../../models/relational/Table.php';
require '../../models/tests/Test.php';


$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $endpoint = $_GET['endpoint'];
        switch ($endpoint) {

            case 'get_tests': // GET di tutti i test di un certo professore la cui mail è passata da FE
                $prof_email = filter_input(INPUT_GET, 'prof_email');
                $data = Test::getProfTests($prof_email);
                break;
        }

        echo json_encode($data);  // Converte l'array $data in JSON e lo invia
        break;

    case 'POST':
        if (isset($_POST['action'])){
            $action = $_POST['action'];
            switch ($action) {
                case 'test_query': //restituisce il risultato della query inserita dal docente per trovare la risposta di una code question
                    $query = filter_input(INPUT_POST, 'query');
                    $data = testQuery($query);
                    echo json_encode($data);

                    break;

                case 'save_test':
                    $data = $_POST['data'];
                    $response = saveTest($data);
                    echo json_encode($response);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;

    case 'PUT':
        if (isset($_GET['action']) && isset($_GET['testId'])) {
            $action = $_GET['action'];
            $testId = $_GET['testId'];
            switch ($action) {
                case 'update_test':
                    $data = $_POST['data'];
                    $response = updateTest($data, $testId);

                    break;
            }
        }

        break;

    case 'DELETE':
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'delete_TT':
                    if(isset($_GET['tableId']) && isset($_GET['testId'])){
                        $testId = $_GET['testId'];
                        $tableId = $_GET['tableId'];
                        $response = Test::deleteTableTestLink($testId, $tableId);
                    }
                    break;

                case 'delete_reference':
                    if(isset($_GET['tab1']) && isset($_GET['tab2']) && isset($_GET['att1']) && isset($_GET['att2'])){
                        Reference::deleteReferenceData($_GET['tab1'], $_GET['tab2'], $_GET['att1'], $_GET['att1']);
                    }
                    break;

                case 'delete_question':
                    if(isset($_GET['questionId']) && isset($_GET['testId']) && isset($_GET['type'])){
                        $testId = $_GET['testId'];
                        $questionId = $_GET['questionId'];
                        $type = $_GET['type'];
                        if($type == 'code'){
                            CodeQuestion::deleteCodeQuestion($testId, $questionId);
                        }
                        else if ($type == 'mc') {
                            MultipleChoiceQuestion::deleteMCData($testId, $questionId);
                        }
                    }
                    break;
            }

        }
        break;
}

//Inserisce a DB un nuovo test creato dal docente
function saveTest($data) {
    try {
        // Decodifica il JSON ricevuto
        $decodedData = json_decode($data, true);

        $creationDate = date('Y-m-d H:i:s');
        $testId = Test::saveTestData($decodedData['title'], $creationDate, true/*$decodedData['show_answers']*/, $_SESSION['email']);

        // Sezione dedicata al salvataggio delle tabelle relative al Test
        $tableIDs = [];
        foreach ($decodedData['tables'] as $table) {
            $tableIDs[] = $table['id'];
        }
        Test::linkTablesToTest($tableIDs, $testId);

        // Sezione dedicata al salvataggio delle referenze relative al Test
        foreach ($decodedData['constraints'] as $referenceData) {
            if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                Reference::saveReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
            }
        }

        // Sezione dedicata al salvataggio delle domande relative al Test
        foreach ($decodedData['questions'] as $questionData) {
            if ($questionData['type'] == 'code') {
                CodeQuestion::saveCodeQuestion($testId,$questionData['id'], $questionData['questionText'], $questionData['sqlCode'], $questionData['difficulty']);
                return 'ok';
            } else if ($questionData['type'] == 'mc') {
                MultipleChoiceQuestion::saveMCData($testId,$questionData['id'],$questionData['questionText'], count($questionData['answers']), $questionData['difficulty'], $questionData['answers'] );
            }
        }
        $response = 'Saved correctly';
    } catch (Exception $exc){
        $response = 'Some error occoured. Error log: ' .$exc;
    }

    return $response;
}
function updateTest($data, $testId) {
    try {
        // Decodifica il JSON ricevuto
        $decodedData = json_decode($data, true);

        if (array_key_exists('title', $decodedData)) {
            Test::updateTestData($testId, $decodedData['title'], true/*$decodedData['show_answers']*/);
        }

        // Sezione dedicata al salvataggio delle tabelle relative al Test
        if (array_key_exists('tables', $decodedData)) {
            $tableIDs = [];
            foreach ($decodedData['tables'] as $table) {
                $tableIDs[] = $table['id'];
            }
            Test::linkTablesToTest($tableIDs, $testId);
        }


        // Sezione dedicata al salvataggio delle referenze relative al Test
        if (array_key_exists('constraints', $decodedData)) {
            foreach ($decodedData['constraints'] as $referenceData) {
                if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                    Reference::saveReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
                }
            }
        }

        // Sezione dedicata al salvataggio delle domande relative al Test
        if (array_key_exists('questions', $decodedData)) {
            foreach ($decodedData['questions'] as $questionData) {
                if ($questionData['type'] == 'code') {
                    CodeQuestion::saveCodeQuestion($testId,$questionData['id'], $questionData['questionText'], $questionData['sqlCode'], $questionData['difficulty']);
                    return 'ok';
                } else if ($questionData['type'] == 'mc') {
                    MultipleChoiceQuestion::saveMCData($testId,$questionData['id'],$questionData['questionText'], count($questionData['answers']), $questionData['difficulty'], $questionData['answers'] );
                }
            }
        }

        $response = 'Updated correctly';
    } catch (Exception $exc){
        $response = 'Some error occoured. Error log: ' .$exc;
    }

    return $response;
}
function testQuery($q){
    global $con;

    if (stripos($q, 'DROP') !== false || stripos($q, 'DELETE') !== false || stripos($q, 'UPDATE') !== false) {
        return 'Query non consentita';
    }

    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
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