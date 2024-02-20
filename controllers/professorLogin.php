<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connessione al database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Preparazione e binding della query
    $stmt = $conn->prepare("SELECT password FROM professors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login successful!";
            // Qui puoi impostare la sessione o reindirizzare l'utente alla pagina protetta
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid email.";
    }

    $stmt->close();
    $conn->close();
}

?>
