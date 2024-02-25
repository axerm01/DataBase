<?php
session_start();
// Da richiamare quando si inserisce ogni domanda

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $test = unserialize($_SESSION['test']);
    $question = new MultipleChoiceQuestion($test->getId(), $_POST['id_question'], $_POST['description'], $_POST['difficulty'], $_POST['num_answers']);
    $test->addQuestion($question);
    $_SESSION['test'] = serialize($test);
    $_SESSION['question'] = serialize($question);

    // Reindirizza alla pagina per aggiungere le risposte
    header("Location: mia_pagina.html");
    exit;
}

?>

