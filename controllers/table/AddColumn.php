<?php

session_start();
// Da richiamare quando si inserisce ogni colonna

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $table = unserialize($_SESSION['table']);
    $column = new Column($table->getId(), $_POST['column_name'], $_POST['type'], $_POST['isPK']);
    $column->addReference($_POST['ref_column_name']);
    $table->addColumn($column);
    $_SESSION['table'] = serialize($table);

    // Reindirizza alla pagina per aggiungere le colonne
    header("Location: mia_pagina.html");
    exit;
}
?>