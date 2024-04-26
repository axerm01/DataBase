<?php
session_start();
include('../utils/connect.php');
include_once '../../models/tests/Test.php';
include_once '../../models/tests/StudentTest.php';
include_once '../../models/tests/StudentAnswer.php';

$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $endpoint = $_GET['action'];
        $data = null;
        switch ($endpoint) {

            case 'get_all_tests': // GET di tutti i test presenti a sistema
                $data = Test::getAllTests();
                break;

            case 'get_filtered_tests': // GET dei test aperti, in progress e chiusi di uno studente
                $stdEmail = $_SESSION['email'];
                $filter = filter_input(INPUT_GET, 'filter');
                $data = StudentTest::getTests($stdEmail, $filter); //Filter può essere Open, InProgress, Close, All
                break;

            case 'get_single_test': // GET del singolo test di uno studente
                $testId = filter_input(INPUT_GET, 'testId');
                $stdEmail = $_SESSION['email'];
                $data = StudentTest::getSingleTest($testId, $stdEmail);
                break;

            case 'start_new_test':
                $testId = filter_input(INPUT_GET, 'testId');
                $data = StudentTest::start($testId);
                break;

            case 'resume_test':
                $testId = filter_input(INPUT_GET, 'testId');
                $stdEmail = $_SESSION['email'];
                $data = StudentTest::resume($testId, $stdEmail);
                break;

        }

        echo json_encode($data);  // Converte l'array $data in JSON e lo invia
        break;

    case 'POST':
        if (isset($_POST['action']) && isset($_POST['testId'])){
            $action = $_POST['action'];
            $email = $_SESSION['email'];
            //$email = $_POST['email'];
            $testId = $_POST['testId'];

            switch ($action) {
                case 'create_student_test':
                    $response = StudentTest::saveStudentTestData($testId, $email);
                    echo json_encode($response);
                    break;

                case 'save_response':
                    if (isset($_POST['first_response_date']) && !empty($_POST['first_response_date'])){
                        $date = $_POST['first_response_date'];
                        StudentTest::setFirstResponseDate($testId, $email, $date);
                        StudentTest::updateStudentTestStatus($testId, $email);
                    }
                    if (isset($_POST['last_response_date']) && !empty($_POST['last_response_date'])){
                        $date = $_POST['last_response_date'];
                        StudentTest::setLastResponseDate($testId, $email, $date);
                    }

                    $stringAnswers = json_decode($_POST['student_answers'], true);
                    $answers = $stringAnswers['answers'];
                    $response = StudentAnswer::saveStudentAnswers($answers, $testId, $email);
                    echo json_encode($response);
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;

    case 'PUT':
        if (isset($_GET['action'])){
            $action = $_GET['action'];
            $email = $_SESSION['email'];

            switch ($action) {
                case 'update_response':
                    if (isset($_GET['testId'])) {
                        $testId = $_GET['testId'];
                        $rawData = file_get_contents('php://input');
                        $data = json_decode($rawData, true);
                        if (isset($data['last_response_date']) && isset($data['student_answers'])) {
                            $responseDate = StudentTest::setLastResponseDate($testId, $email, $data['last_response_date']);
                            $answers = $data['student_answers'];
                            $oldResponseAnswers = "";
                            $newResponseAnswers = "";
                            if(isset($answers['old_answers']) && !empty($answers['old_answers'])){
                                $oldResponseAnswers = StudentAnswer::updateStudentAnswers($answers['old_answers'], $testId, $email);
                            }
                            if(isset($answers['new_answers']) && !empty($answers['new_answers'])){
                                $newResponseAnswers = StudentAnswer::saveStudentAnswers($answers['new_answers'], $testId, $email);
                            }

                            $response = ['dateResponse' => $responseDate, 'answersResponseOld' => $oldResponseAnswers, 'answersResponseNew' => $newResponseAnswers];
                            echo json_encode($response);
                        } else {
                            echo json_encode(['error' => 'Missing data for update']);
                        }
                    } else {
                        echo json_encode(['error' => 'Test ID not provided']);
                    }
                    break;
            }
        }
        else {
            echo json_encode("no action");
        }
        break;
}