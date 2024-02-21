<?php
// Includi il file di configurazione del database (modifica con il tuo file di configurazione)
include 'db_config.php';

// Controlla se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Controlla se l'email è valida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato email non valido!";
        exit;
    }

    // Controlla se la password è valida
    if (!preg_match("/^[a-zA-Z0-9]*$/", $password) || !preg_match("/[A-Z]/", $password)) {
        echo "La password può contenere solo lettere e numeri e deve avere almeno una lettera maiuscola!";
        exit;
    }

    // Crea una connessione al database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Controlla la connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Cripta la password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepara e lega
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password_hash);

    // Esegui
    if ($stmt->execute()) {
        echo "Registrazione effettuata in modo corretto!";
    } else {
        echo "Si è verificato un errore durante la registrazione.";
    }

    // Chiudi la connessione
    $conn->close();
}
?>
