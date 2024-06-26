<?php
session_start();
include_once('../utils/connect.php');
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
            try {
                switch ($endpoint) {

                    case 'get_tests': // GET di tutti i test di un certo professore la cui mail è nella sessione
                        $prof_email = $_SESSION['email'];
                        $data = Test::getProfTests($prof_email);
                        break;

                    case 'get_test_content': // GET del contenuto di un test
                        $testId = $_GET['testId'];
                        $data = Test::getTestContent($testId);
                        break;

                    case 'check_name':
                        $name = filter_input(INPUT_GET, 'name');
                        $data = Test::checkIfTestNameExists($name);
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

    case 'POST':
        if (isset($_POST['action'])){
            $action = $_POST['action'];
            try {
                switch ($action) {
                    case 'test_query':
                        $query = filter_input(INPUT_POST, 'query');
                        $response = Test::testQuery($query);
                        break;

                    case 'save_test':
                        $data = $_POST['data'];
                        if (isset($_FILES['testImage'])) {
                            $imageFile = $_FILES['testImage'];
                            $response = saveTest($data, $imageFile);
                        } else {
                            $response = saveTest($data, null);
                        }
                        break;

                    case 'update_test':
                        $jsondata = $_POST['data'];
                        $data = json_decode($jsondata, true);
                        $testId = $data['testId'];
                        $response = updateTest($data, $testId);
                        break;
                }
            } catch (Exception $e){
                $response = "Errore: ".$e->getMessage();
            }
            echo json_encode($response);

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

    case 'DELETE':
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            try {
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
                            $response = Reference::deleteReferenceData($_GET['tab1'], $_GET['tab2'], $_GET['att1'], $_GET['att1']);
                        }
                        break;

                    case 'delete_question':
                        if(isset($_GET['questionId']) && isset($_GET['testId']) && isset($_GET['type'])){
                            $testId = $_GET['testId'];
                            $questionId = $_GET['questionId'];
                            $type = $_GET['type'];
                            if($type == 'code'){
                                $response = CodeQuestion::deleteCodeQuestion($testId, $questionId);
                            }
                            else if ($type == 'mc') {
                                $response = MultipleChoiceQuestion::deleteMCData($testId, $questionId);
                            }
                        }
                        break;
                }
            }catch (Exception $e){
                $response = ("Errore: ".$e->getMessage());
            }
            echo json_encode($response);
        } else {
            echo json_encode("no action");
        }
        break;
}

function saveTest($data, $image) {
    try {
        $decodedData = json_decode($data, true);
        $testId = Test::saveTestData($decodedData['title'], $decodedData['viewAnswersPermission'], $_SESSION['email'], $image);

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

        $response = 'Test salvato correttamente';
    } catch (Exception $exc){
        $response = 'Errore: ' .$exc->getMessage();
    }
    return $response;
}
function updateTest($decodedData, $testId) {
    try {
        if ($decodedData['viewAnswersPermission'] == true){
            Test::updateVisualizzaRisposte($testId, true);
        }
        else if ($decodedData['viewAnswersPermission'] == false){
            Test::updateVisualizzaRisposte($testId, false);
        }


        //per ora non possibile da frontend
        /*if (array_key_exists('title', $decodedData) && !empty($decodedData['title'])) {
            Test::updateTestTitle($testId, $decodedData['title']);
        }

        if (array_key_exists('tables', $decodedData) && !empty($decodedData['tables'])) {
            $tableIDs = [];
            foreach ($decodedData['tables'] as $table) {
                $tableIDs[] = $table['id'];
            }
            Test::linkTablesToTest($tableIDs, $testId);
        }

        if (array_key_exists('constraints', $decodedData) && !empty($decodedData['constraints'])) {
            foreach ($decodedData['constraints'] as $referenceData) {
                if (isset($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2'])) {
                    Reference::saveReferenceData($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
                }
            }
        }

        //per ora non possibile da frontend
        if (array_key_exists('questions_updated', $decodedData) && !empty($decodedData['questions_updated'])) {
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

        if (array_key_exists('questions_added', $decodedData) && !empty($decodedData['questions_added'])) {
            $added = $decodedData['questions_added'];
                foreach ($added as $questionData) {
                    if ($questionData['type'] == 'code') {
                        CodeQuestion::saveCodeQuestion($testId,$questionData['id'], $questionData['questionText'], $questionData['sqlCode'], $questionData['difficulty']);
                    } else if ($questionData['type'] == 'mc') {
                        MultipleChoiceQuestion::saveMCData($testId,$questionData['id'],$questionData['questionText'], count($questionData['answers']), $questionData['difficulty'], $questionData['answers'] );
                    }
                }
        }

        //per ora non possibile da frontend
        if (array_key_exists('deleted', $decodedData) && !empty($decodedData['deleted'])) {
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
        }*/

        $response = 'Tutte le modifiche sono state salvate';
    } catch (Exception $exc){
        $response = 'Errore: ' .$exc->getMessage();
    }
    return $response;
}
