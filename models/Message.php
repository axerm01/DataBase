<?php
include('../controllers/utils/connect.php');

class Message {
    public static function newProfessorMessage($title, $text, $testId, $dateTime, $profEmail){ //registra un nuovo messaggio del professore
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

    public static function newStudentMessage($title, $text, $testId, $dateTime, $studentEmail){ //registra un nuovo messaggio dello studente
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

    public static function getProfessorMessages($prof_email) { // ottieni tutti i messaggi inviati da un certo professore
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

    public static function getStudentMessages($student_email) { //ottieni tutti i messaggi inviati da un certo studente
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

    public static function getTestMessages($testId){ //ottieni tutti i messaggi che il prof ha scritto per un certo test
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

    public static function createMessageObjects($messages) {
        $messageObjects = [];

        foreach ($messages as $message) {
            $messageObj = new Message($message['title'], $message['text'], $message['date_time'], $message['test_id'], $message['email']);
            array_push($messageObjects, $messageObj);
        }

        return $messageObjects;
    }
}