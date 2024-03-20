<?php
session_start();
include('../utils/connect.php');
include '../../models/users/Student.php';

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'];
//$uri = explode('?', $_SERVER['REQUEST_URI'], 2);
//$endpoint = $uri[0];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        switch ($endpoint) {
            /*case 'show_test':
                $testId = filter_input(INPUT_GET, 'testId');
                $data = Test::showInfo($testId);
                break;*/

            case 'start_test':
                $testId = filter_input(INPUT_GET, 'testId');
                $stdEmail = $_SESSION['email'];
                StudentTest::start($testId, $stdEmail);
                $data = Question::getQuestion(0,$testId);
                break;

            case 'next_question': //se $data è NULL significa che si è raggiunta la fine del test, mettere controllo sul frontend
                $qId = filter_input(INPUT_GET, 'questionId');
                $testId = filter_input(INPUT_GET, 'testId');
                $data = Question::getQuestion($qId,$testId);
                break;

            case 'get_tests': // GET di tutti i test presenti a sistema
                $data = Test::getAllTests();
                break;

            case 'get_my_tests': // GET dei test aperti, in progress e chiusi di uno studente
                $testId = filter_input(INPUT_GET, 'testId');
                $stdEmail = $_SESSION['email'];
                $data = StudentTest::getTests($testId, $stdEmail);
                break;

        }
        echo json_encode($data);  // Converte l'array $data in JSON e lo invia

        break;

    case 'POST':
        break;

    case 'PUT':
        break;

    case 'DELETE':
        break;

}

?>

