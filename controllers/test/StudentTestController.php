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
            case 'start_test':
                $testId = filter_input(INPUT_GET, 'testId');
                $stdEmail = $_SESSION['email'];
                $data = startTest($testId, $stdEmail);
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



function startTest($testId, $stdEmail){
    $data = '';
    $student = Student::getStudent($stdEmail);
    $student->getStudentTestList();


    return $data;
}



?>

