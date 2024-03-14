<?php
$servername = "localhost";
$username = "root"; // il nome utente predefinito per MySQL in XAMPP è 'root'
$password = ""; // la password predefinita per MySQL in XAMPP è vuota
$dbname = "databaseprova_1"; // sostituisci con il nome del tuo database

// Crea connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}
echo "Connessione riuscita";

