<?php
session_start();
include('../utils/connect.php');
include_once '../../models/relational/Table.php';
include_once '../../models/tests/Test.php';
include_once '../../models/tests/CodeQuestion.php';
include_once '../../models/tests/MultipleChoiceQuestion.php';
include_once '../../models/relational/Reference.php';


$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        if (isset($_GET['action'])){
            $endpoint = $_GET['action'];
            switch ($endpoint) {

                case 'get_tests': // GET di tutti i test di un certo professore la cui mail è nella sessione
                    //$prof_email = filter_input(INPUT_GET, 'prof_email');
                    $prof_email = $_SESSION['email'];
                    $data = Test::getProfTests($prof_email);
                    break;

                case 'get_test_content': // GET del contenuto di un test per poterlo modificare
                    $testId = $_GET['testId'];
                    $data = Test::getTestContent($testId);
                    break;
            }
            echo json_encode($data);  // Converte l'array $data in JSON e lo invia

        } else {
            echo json_encode("no action");
        }
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
                    if (isset($_FILES['testImage'])) {
                        $imageFile = $_FILES['testImage'];
                        $response = saveTest($data, $imageFile);
                    } else {
                        $response = saveTest($data, null);
                    }
                    echo json_encode($response);
                    break;

                case 'update_test':
                    $data = $_POST['data'];
                    $testId = $data['testId'];
                    $response = updateTest($data, $testId);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;

    /*case 'PUT':
        if (isset($_GET['action']) && isset($_GET['testId'])) {
            $action = $_GET['action'];
            $testId = $_GET['testId'];
            switch ($action) {
                case 'update_test':
                    $data = $_POST['data'];
                    $response = updateTest($data, $testId);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;*/

    /*case 'DELETE':
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
        } else {
            echo json_encode("no action");
        }
        break;*/
}

//Inserisce a DB un nuovo test creato dal docente
function saveTest($data, $image) {
    try {
        // Decodifica il JSON ricevuto
        $decodedData = json_decode($data, true);

        $creationDate = date('Y-m-d H:i:s');
        $testId = Test::saveTestData($decodedData['title'], $creationDate, $decodedData['viewAnswersPermission'], $_SESSION['email'], $image);

        // Sezione dedicata al salvataggio delle tabelle relative al Test
        $tableIDs = [];
        foreach ($decodedData['tables'] as $table) {
            $tableIDs[] = $table['id'];
        }
        Test::linkTablesToTest($tableIDs, $testId);

        // Sezione dedicata al salvataggio delle referenze relative al Test
        if(!empty($decodedData['constraints'])){
            foreach ($decodedData['constraints'] as $referenceData) {
                if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                    Reference::saveReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
                }
            }
        }

        // Sezione dedicata al salvataggio delle domande relative al Test
        if(!empty($decodedData['questions'])){
            foreach ($decodedData['questions'] as $questionData) {
                if ($questionData['type'] == 'code') {
                    CodeQuestion::saveCodeQuestion($testId,$questionData['id'], $questionData['questionText'], $questionData['sqlCode'], $questionData['difficulty']);
                } else if ($questionData['type'] == 'mc') {
                    MultipleChoiceQuestion::saveMCData($testId,$questionData['id'],$questionData['questionText'], count($questionData['answers']), $questionData['difficulty'], $questionData['answers'] );
                }
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
            Test::updateTestTitle($testId, $decodedData['title']);
        }

        if (array_key_exists('tables', $decodedData)) {
            $tableIDs = [];
            foreach ($decodedData['tables'] as $table) {
                $tableIDs[] = $table['id'];
            }
            Test::linkTablesToTest($tableIDs, $testId);
        }

        if (array_key_exists('constraints', $decodedData)) {
            foreach ($decodedData['constraints'] as $referenceData) {
                if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                    Reference::saveReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
                }
            }
        }

        if (array_key_exists('questions_updated', $decodedData)) {
            $updated = $decodedData['questions_updated'];
            // Sezione dedicata all'aggiornamento delle domande relative al Test
                foreach ($updated as $questionData) {
                    if ($questionData['type'] == 'code') {
                        if (array_key_exists('questionText', $questionData)) {
                            CodeQuestion::updateCodeQuestionText($testId,$questionData['id'], $questionData['questionText']);
                        }
                        if (array_key_exists('sqlCode', $questionData)) {
                            CodeQuestion::updateCodeQuestionSql($testId,$questionData['id'], $questionData['sqlCode']);
                        }
                        if (array_key_exists('difficulty', $questionData)) {
                            CodeQuestion::updateCodeQuestionDiff($testId,$questionData['id'], $questionData['difficulty']);
                        }

                    } else if ($questionData['type'] == 'mc') {
                        if (array_key_exists('questionText', $questionData)) {
                            MultipleChoiceQuestion::updateMCText($testId,$questionData['id'], $questionData['questionText']);
                        }
                        if (array_key_exists('difficulty', $questionData)) {
                            MultipleChoiceQuestion::updateMCDiff($testId,$questionData['id'], $questionData['difficulty']);
                        }
                        if (array_key_exists('updated_answers', $questionData)) {
                            MultipleChoiceQuestion::updateMCAnswers($testId,$questionData['id'], $questionData['updated_answers']);
                        }
                        if (array_key_exists('new_answers', $questionData)) {
                            MultipleChoiceQuestion::updateMCNumAnswers($testId,$questionData['id'], count($questionData['new_answers']));
                            foreach ($questionData['new_answers'] as $answer) {
                                Answer::saveMCAnswersData($answer['id'],$testId,$questionData['id'],$answer['text'],$answer['isCorrect'] );
                            }
                        }
                        if (array_key_exists('deleted_answers', $questionData)) {
                            MultipleChoiceQuestion::updateMCNumAnswers($testId,$questionData['id'], -1*count($questionData['deleted_answers']));
                            foreach ($questionData['deleted_answers'] as $answer) {
                                Answer::deleteMCSingleAnswer($testId,$questionData['id'],$answer['id']);
                            }
                        }
                    }
                }
            }

        if (array_key_exists('questions_added', $decodedData)) {
            $added = $decodedData['questions_added'];
                foreach ($added as $questionData) {
                    if ($questionData['type'] == 'code') {
                        CodeQuestion::saveCodeQuestion($testId,$questionData['id'], $questionData['questionText'], $questionData['sqlCode'], $questionData['difficulty']);
                    } else if ($questionData['type'] == 'mc') {
                        MultipleChoiceQuestion::saveMCData($testId,$questionData['id'],$questionData['questionText'], count($questionData['answers']), $questionData['difficulty'], $questionData['answers'] );
                    }
                }
        }

        if (array_key_exists('deleted', $decodedData)) {
            $deleted = $decodedData['deleted'];
            if (array_key_exists('tables', $deleted)) {
                $tableIDs = [];
                foreach ($deleted['tables'] as $table) {
                    $tableIDs[] = $table['id'];
                }
                Test::deleteTableTestLink($tableIDs, $testId);
            }
            if (array_key_exists('constraints', $deleted)) {
                foreach ($deleted['constraints'] as $referenceData) {
                    if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                        Reference::deleteReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
                    }
                }
            }
            if (array_key_exists('questions', $deleted)) {
                foreach ($deleted as $questionData) {
                    if ($questionData['type'] == 'code') {
                        CodeQuestion::deleteCodeQuestion($testId,$questionData['id']);
                    } else if ($questionData['type'] == 'mc') {
                        MultipleChoiceQuestion::deleteMCData($testId,$questionData['id']);
                    }
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

    if (stripos($q, 'INSERT') !== false || stripos($q, 'DROP') !== false || stripos($q, 'DELETE') !== false || stripos($q, 'UPDATE') !== false) {
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