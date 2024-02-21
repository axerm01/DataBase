<?php
// Avvia la sessione
session_start();

// Includi il file di configurazione del database (modifica con il tuo file di configurazione)
include 'db_config.php';

// Controlla se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Controlla se l'email ha il dominio corretto
    if (strpos($username, '@unibo.it') !== false || strpos($username, '@studio.unibo.it') !== false) {
        // Crea una connessione al database
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Controlla la connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Prepara e lega
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        // Esegui
        $stmt->execute();

        // Ottieni il risultato
        $result = $stmt->get_result();

        // Controlla se l'utente esiste
        if ($result->num_rows > 0) {
            // Ottieni i dati dell'utente
            $user = $result->fetch_assoc();

            // Verifica la password
            if (password_verify($password, $user['password'])) {
                // Imposta le variabili di sessione
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;

                // Reindirizza alla pagina dell'account in base al ruolo dell'utente
                if ($user['role'] == 'docente') {
                    header("location: homeDocenti.html");
                } else if ($user['role'] == 'studente') {
                    header("location: homeStudenti.html");
                }
            } else {
                echo "Password errata!";
            }
        } else {
            echo "Utente non trovato!";
        }

        // Chiudi la connessione
        $conn->close();
    } else {
        echo "Il dominio dell'email non è valido. Deve essere '@unibo.it' o '@studio.unibo.it'.";
    }
}
?>
