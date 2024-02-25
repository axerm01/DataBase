<?php
session_start();

// Da richiamare quando si inserisce il test, prima delle domande

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $test = new Test(0, $_POST['test_title'], $_POST['test_creation_date'], $_POST['show_answers'], $_POST['prof_email']);
    $test->setPhoto($_POST['test_photo']);
    $_SESSION['test'] = serialize($test);

    // Reindirizza alla pagina per aggiungere le domande
    header("Location: mia_pagina.html");
    exit;
}

?>

