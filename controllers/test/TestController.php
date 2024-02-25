<?php
session_start();
// Da richiamare quando si termina l'inserimento di tutte le domande del test

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SESSION['test']))) {
    $test = unserialize($_SESSION['test']);

    //Query DB per creaz test, domande, risposte

    unset($_SESSION['table']);
}

?>

