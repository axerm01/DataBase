<?php
session_start();
include_once('../../models/users/Message.php');

// Controlla se l'utente è loggato
if(isset($_SESSION['email']) && isset($_SESSION['role'])){
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
}
else {
    header('Location: ../views/login.html');
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        if (isset($_GET['action'])) {
            $endpoint = $_GET['action'];
            $data = "no data";
            switch ($endpoint) {
                case 'get_messages': // GET di tutti i messaggi destinati a un utente
                    if ($role == 'student') {
                        $data = Message::getProfMessages();
                    }
                    if ($role == 'professor') {
                        $testId = $_GET['testId'];
                        $data = Message::getStudentMessages($testId);
                    }
                    break;
            }

            echo json_encode($data);  // Converte l'array $data in JSON e lo invia

        } else {
            echo json_encode("no action");
        }
        break;

    case 'POST':
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            switch ($action) {
                case 'send_prof_message':
                    if ($role == 'professor') {
                        $message = json_decode($_POST['message'], true);
                        $dateTime = date('Y-m-d H:i:s');
                        $response = Message::sendProfMessage($message['testId'], $email, $message['titolo'], $message['testo'], $dateTime);
                        echo json_encode($response);
                    }
                    break;

                case 'send_student_message':
                    if ($role == 'student') {
                        $message = json_decode($_POST['message'], true);
                        $dateTime = date('Y-m-d H:i:s');
                        $response = Message::sendStudentMessage($message['testId'], $email, $message['titolo'], $message['testo'], $dateTime);
                        echo json_encode($response);
                    }
                    break;
            }
        } else {
            echo json_encode("no action");
        }
        break;
}