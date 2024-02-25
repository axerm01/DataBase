<?php

session_start();
// Da richiamare quando si termina l'inserimento di tutte le colonne della tabella

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SESSION['table']))) {
    $table = unserialize($_SESSION['table']);

    //Query DB per creaz tabella

    unset($_SESSION['table']);
}

?>

