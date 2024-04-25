<?php
session_start();
include_once '../../models/users/Statistics.php';

$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');

$endpoint = $_GET['action'];
$data = null;
switch ($endpoint) {

    case 'test_completati':
        $data = Statistics::classificaTestCompletati();
        break;

    case 'risposte_corrette':
        $data = Statistics::classificaRisposteCorrette();
        break;

    case 'quesiti':
        $data = Statistics::classificaQuesiti();
        break;
}
echo json_encode($data);