<?php

session_start();

// Da richiamare quando si inserisce la tabella, prim delle colonne

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $table_name = $_POST['table_name'];
    $table = new Table(0, $table_name);
    $_SESSION['table'] = serialize($table);

    // Reindirizza alla pagina per aggiungere le colonne
    header("Location: mia_pagina.html");
    exit;
}

?>
