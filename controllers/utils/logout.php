<?php
// Inizia la sessione
session_start();

if (isset($_POST['logout'])) {
    // Rimuove tutte le variabili di sessione
    session_unset();

    // Distrugge la sessione
    session_destroy();

    // Reindirizza alla pagina di login
    header("Location: login.php");
    exit();
}