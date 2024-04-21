<?php
session_start(); // Avvia la sessione
include_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    global $con;
    // reparazione e binding della query
    if($role == 'professor'){
        $stmt = $con->prepare("SELECT password FROM Docente WHERE mail = ?");
    }
    else if($role == 'student'){
        $stmt = $con->prepare("SELECT password FROM Studente WHERE mail = ?");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($password == $row['password']) {
            echo "Login successful!";

            // Imposta variabili di sessione
            $_SESSION["loggedin"] = true;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;

            if($role == 'professor'){
                header('Location: ../../views/prof_home.html');
            }
            else if ($role == 'student'){
                header('Location: ../../views/student_home.html');
            }

        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid email.";
    }

    $stmt->close();
    $con->close();
}
