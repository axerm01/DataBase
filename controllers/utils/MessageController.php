<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../models/Message.php');

// Controlla se l'utente è loggato
if(isset($_SESSION['email']) && isset($_SESSION['role'])){
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
}
else {
    header('Location: ../views/login.html');
    exit();
}

// Memorizza i valori passati dal frontend
$action = filter_input(INPUT_POST, 'action');
if(!$action) {
    $action = filter_input(INPUT_GET, 'action');
}

$title = filter_input(INPUT_POST, 'title');
$text = filter_input(INPUT_POST, 'text');
$testId = filter_input(INPUT_POST, 'testId');

switch ($action){
    case 'new_student_message': // POST
        $dateTime = date('Y-m-d H:i:s');
        newStudentMessage($title, $text, $testId, $dateTime, $email);
        header('Location: ../views/home.php');
        break;

    case 'new_professor_message': // POST
        $dateTime = date('Y-m-d H:i:s');
        echo "  Si";
        newProfessorMessage($title, $text, $testId, $dateTime, $email);
        header('Location: ../views/home.php');
        break;

    case 'get_student_messages': //GET
        $messages = getStudentMessages($email);
        include('../views/messageList.php');
        break;

    case 'get_professor_messages': //GET
        $messages = getProfessorMessages($email);
        include('../views/messageList.php');
        break;

    case 'get_test_messages': //GET
        $messages = getTestMessages($testId);
        include('../views/messageList.php');
        break;
}


/*class MessageController {
    private $students;
    private $professors;
    private $tests;

    public function __construct($studentList, $professorList, $testList) {
        $this->students = $studentList;
        $this->professors = $professorList;
        $this->tests = $testList;
    }

    public function findProfessorEmailByTestID($IDTest){
        return $this->tests->getTestById($IDTest)->getProfessorEmail();
    }

    public function sendMessageToProfessor($title, $text, $date, $IDTest, $studentEmail) {
        $professorEmail = $this->findProfessorEmailByTestID($IDTest);
        $message = new Message($title, $text, $date, $IDTest, $studentEmail, $professorEmail);
        $this->professors->getProfessorByEmail($professorEmail)->receiveMessage($message);
    }

    public function sendBroadcastMessageFromProfessor($title, $text, $date, $IDTest, $professorEmail) {
        foreach ($this->students->getStudents() as $studentEmail => $student) {
            $message = new Message($title, $text, $date, $IDTest, $professorEmail, $studentEmail);
            $student->receiveMessage($message);
        }
    }

}*/

?>