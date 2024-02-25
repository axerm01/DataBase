<?php
session_start();
// Da richiamare quando si inserisce ogni domanda

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $test = unserialize($_SESSION['test']);
    $question = new CodeQuestion($test->getId(), $_POST['id_question'], $_POST['output']);
    $test->addQuestion($question);
    $_SESSION['test'] = serialize($test);

    // Reindirizza alla pagina per aggiungere le colonne
    header("Location: mia_pagina.html");
    exit;
}

?>

