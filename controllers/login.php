<?php
session_start(); // Avvia la sessione

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Connessione al database, da modificare con param reali
    include('connect.php');
    global $con;

    // Preparazione e binding della query
    if($role == 'professor'){
        $stmt = $con->prepare("SELECT password FROM Professor WHERE email = ?");
    }
    else if($role == 'student'){
        $stmt = $con->prepare("SELECT password FROM Student WHERE email = ?");
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

            header('Location: ../views/home.php');
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid email.";
    }

    $stmt->close();
    $con->close();
}

?>
