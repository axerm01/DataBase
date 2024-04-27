<?php
include_once '../../controllers/utils/connect.php';

class Professor {

    public static function signin($name, $surname, $email, $course, $department, $phone, $pwd) {
        global $con;

        if ($con->connect_error) {
            die("Connessione fallita: " . $con->connect_error);
        }
        $stmt = $con->prepare("CALL CreateDocente (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $name,$surname, $email, $course, $department, $phone, $pwd);

        if ($stmt->execute()) {
            // Invece di restituire JSON, reindirizza a una pagina di successo
            header('Location: ../../views/login.html');
            exit;
        } else {
            $messaggio = "Si è verificato un errore durante la registrazione.";
        }
        $con->close();

        return json_encode(["message" => $messaggio]);
    }

    public static function login($email, $password) {
        global $con;
        $stmt = $con->prepare("CALL GetProfessorPassword(?)");
        if ($stmt === false) {
            return "Errore nella preparazione della query: " . $con->error;
        }
        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) {
            return  "Errore nell'esecuzione della query: " . $stmt->error;
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