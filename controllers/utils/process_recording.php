<?php
// Includi il file di configurazione del database 
///////////////////modifica con il file giusto 
include 'db_config.php';
include "recording.php";

// Controlla se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Controlla se l'email è valida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato email non valido!";
        exit;
    }
    // Controlla se l'email ha il dominio corretto
    if (!preg_match('/@(studio\.)?unibo\.it$/', $email)) {
        echo "L'email deve essere di dominio @studio.unibo.it o @unibo.it!";
        exit;
    }
    // Controlla se la password è valida
    if (!preg_match("/^[a-zA-Z0-9]*$/", $password) || !preg_match("/[A-Z]/", $password)) {
        echo "La password può contenere solo lettere e numeri e deve avere almeno una lettera maiuscola!";
        exit;
    }
    // Cripta la password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);






//////////////////////// Gioele controlla da qui in poi se manda bene i dati al Database... 
    // Crea una connessione al database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Controlla la connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password_hash);






    // Pezzo di codice che dovrebe restituire un riscontro
    if ($stmt->execute()) {
        $messaggio = "Registrazione effettuata in modo corretto!";
    } else {
        $messaggio = "Si è verificato un errore durante la registrazione.";
    }
    // Restituisci il messaggio come risposta JSON
    echo json_encode(["message" => $message]);

    // Chiudi la connessione
    $conn->close();
}
?>
