<?php
session_start();
include('../utils/connect.php');
include '../../models/users/Student.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $action = filter_input(INPUT_GET, 'action');

    $data = 'no data';
    switch ($action) {
        case 'start_test':
            $testId = filter_input(INPUT_GET, 'id');
            $stdEmail = $_SESSION['email'];

            $data = startTest($testId, $stdEmail);
            break;

        case 'get_tests': // GET di tutti i test di un certo professore la cui mail è passata da FE
            $prof_email = filter_input(INPUT_GET, 'prof_email');
            $data = getAllTests($prof_email);
            break;

        case 'get_tables': // GET delle tabelle create da un docente, restituisce id tabella e nome
            $data = getAllTables();
            break;

        case 'get_table_columns': // GET delle colonne di una tabella indicata
            $id = filter_input(INPUT_GET, 'tableId');
            $data = getTableColumns($id);
            break;
    }

    header('Content-Type: application/json');  // Imposta l'header per il contenuto JSON
    echo json_encode($data);  // Converte l'array $data in JSON e lo invia
    }

function startTest($testId, $stdEmail){
    $data = '';
    $student = Student::getStudent($stdEmail);
    $student->getStudentTestList();


    return $data;
}



?>
