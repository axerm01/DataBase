<?php
session_start();
include('../utils/connect.php');
include '../../models/tests/Test.php';
include '../../models/tests/StudentTest.php';
include '../../models/tests/StudentAnswer.php';

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
            $testId = $_POST['testId'];

            switch ($action) {
                case 'create_student_test':
                    $dataPrima = $_POST['data_prima'];
                    $dataUltima = $_POST['data_ultima'];
                    StudentTest::saveStudentTestData($dataPrima, $dataUltima, $testId, $email);
                    echo json_encode("student test created correctly");
                    break;

                case 'save_response':
                    $answers = json_decode($_POST['student_answers'], true);
                    StudentAnswer::saveStudentAnswers($answers, $testId, $email);
                    echo json_encode("response saved correctly");
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;

    case 'PUT':
        if (isset($_GET['testId']) && isset($_GET['action'])){
            $testId = $_GET['testId'];
            $action = $_GET['action'];
            $email = $_SESSION['email'];

            switch ($action) {
                case 'status_in_progress':
                    StudentTest::updateStudentTestStatus($testId, $email);
                    echo json_encode('updated status: in progress');
                    break;

                case 'update_response':
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);
                    $stdEmail = $_SESSION['email'];
                    StudentTest::updateStudentTestData($testId, $email);
                    StudentAnswer::updateStudentAnswers($data, $testId, $email);
                    echo "update correctly";
                    break;
            }
        }
        else {
            echo json_encode("no action");
        }
        break;

    case 'DELETE':
        break;

}