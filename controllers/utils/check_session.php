<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Controlla se l'utente è loggato
if(isset($_SESSION['email']) && isset($_SESSION['role'])){
    // L'utente è autenticato, restituisci una risposta "logged_in"
    echo "logged_in";
}else{
    echo "not_logged_in"; // Redirigi all'accesso se non loggato
    exit;
}