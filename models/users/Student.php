<?php
include_once '../../controllers/utils/connect.php';

class Student
{
    public static function signin($name, $surname, $email, $mat, $year, $phone, $pwd ) {
        global $con;

        if ($con->connect_error) {
            throw new Exception ("Connessione fallita: " . $con->connect_error);
        }
        $stmt = $con->prepare("CALL CreateStudent (?,?,?,?,?,?,?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param("sssssss", $name,$surname, $email, $mat, $year, $phone, $pwd);

        if ($stmt->execute()) {
            $con->close();
            logMongo('Registrazione di un nuovo studente: '.$email);
            header('Location: ../../views/login.html');
            exit;
        } else {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }
    }

    public static function login($email, $password) {
        global $con;
        $stmt = $con->prepare("CALL GetStudentPassword(?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) {
            throw new Exception  ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $hash = $result->fetch_assoc();
        $stmt->close();

        if (password_verify($password, $hash['password'])) {
            return true;
        } else {
            return false;
        }
    }
}