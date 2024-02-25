<?php

session_start();
// Da richiamare quando si inserisce ogni risposta ad una domanda

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $question = unserialize($_SESSION['question']);
    $answer = new Answer($_POST['id_answer'], $_POST['text']);
    $question->addAnswer($answer);
    $_SESSION['question'] = serialize($question);

    // Reindirizza alla pagina per aggiungere le risposte
    header("Location: mia_pagina.html");
    exit;
}

?>

