<?php
include('../controllers/connect.php');

function newProfessorMessage($title, $text, $testId, $dateTime, $profEmail){ //registra un nuovo messaggio del professore
    global $con;
    $q = "INSERT INTO ProfessorMessage (title, text, date_time, test_id, prof_email) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 'sssis', $title, $text, $dateTime, $testId, $profEmail);
    if (!mysqli_stmt_execute($stmt)) {
        die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);

}

function newStudentMessage($title, $text, $testId, $dateTime, $studentEmail){ //registra un nuovo messaggio dello studente
    global $con;
    $q = "INSERT INTO StudentMessage (title, text, date_time, test_id, student_email) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 'sssis', $title, $text, $dateTime, $testId, $studentEmail);
    if (!mysqli_stmt_execute($stmt)) {
        die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
}

function getProfessorMessages($prof_email) { // ottieni tutti i messaggi inviati da un certo professore
    global $con;
    $q = "SELECT title, text, date_time, test_id, prof_email as email FROM ProfessorMessage WHERE prof_email = ?";
    $stmt = mysqli_prepare($con, $q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 's', $prof_email);
    if (!mysqli_stmt_execute($stmt)) {
        die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return createMessageObjects($messages);
}

function getStudentMessages($student_email) { //ottieni tutti i messaggi inviati da un certo studente
    global $con;
    $q = "SELECT title, text, date_time, test_id, student_email as email FROM StudentMessage WHERE student_email = ?";
    $stmt = mysqli_prepare($con, $q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 's', $student_email);
    if (!mysqli_stmt_execute($stmt)) {
        die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return createMessageObjects($messages);
}


function getTestMessages($testId){ //ottieni tutti i messaggi che il prof ha scritto per un certo test
    global $con;
    $q = "SELECT title, text, date_time, test_id, prof_email as email FROM ProfessorMessage WHERE test_id = ?";
    $stmt = mysqli_prepare($con, $q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 's', $testId);
    if (!mysqli_stmt_execute($stmt)) {
        die("Errore nell'esecuzione della query: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return createMessageObjects($messages);
}

function createMessageObjects($messages) {
    $messageObjects = [];

    foreach ($messages as $message) {
        $messageObj = new Message($message['title'], $message['text'], $message['date_time'], $message['test_id'], $message['email']);
        array_push($messageObjects, $messageObj);
    }

    return $messageObjects;
}

class Message {
    private $title;
    private $text;
    private $date;
    private $IDTest;
    private $sender;

    public function __construct($title, $text, $date, $IDTest, $sender) {
        $this->title = $title;
        $this->text = $text;
        $this->date = $date;
        $this->IDTest = $IDTest;
        $this->sender = $sender;
    }

    // Getter per title
    public function getTitle() {
        return $this->title;
    }

    // Setter per title
    public function setTitle($title) {
        $this->title = $title;
    }

    // Getter per text
    public function getText() {
        return $this->text;
    }

    // Setter per text
    public function setText($text) {
        $this->text = $text;
    }

    // Getter per date
    public function getDate() {
        return $this->date;
    }

    // Setter per date
    public function setDate($date) {
        $this->date = $date;
    }

    // Getter per IDTest
    public function getIDTest() {
        return $this->IDTest;
    }

    // Setter per IDTest
    public function setIDTest($IDTest) {
        $this->IDTest = $IDTest;
    }

    public function getSender() {
        return $this->sender;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }
}


?>