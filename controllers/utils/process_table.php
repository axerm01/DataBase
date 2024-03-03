<?php
    // Recupera i dati dal modulo HTML
    $title = $_POST['title'];
    $attributes = $_POST['attributes'];

    // Stabilisce una connessione con il database MySQL
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "myDB";

    // Crea la connessione
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Controlla la connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Crea la query SQL
    $sql = "CREATE TABLE $title (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY";

    for ($i = 1; $i <= $attributes; $i++) {
        $sql .= ", attribute$i VARCHAR(30) NOT NULL";
    }

    $sql .= ")";

    // Invia la query SQL al database
    if ($conn->query($sql) === TRUE) {
        echo "Tabella $title creata con successo";
    } else {
        echo "Errore nella creazione della tabella: " . $conn->error;
    }

    // Chiude la connessione
    $conn->close();
?>
