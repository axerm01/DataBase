<?php
// Inizia la sessione
session_start();

if (isset($_POST['logout'])) {
    // Rimuove tutte le variabili di sessione   session_unset();
    $_SESSION["loggedin"] = false;
    $_SESSION["email"] = "";
    $_SESSION["role"] = "";
    // Distrugge la sessione
    session_destroy();

    // Reindirizza alla pagina di login
    header("Location: ../../views/login.html");
    exit();
}