<?php
include_once 'connect.php';
include_once '../../models/users/Student.php';
include_once '../../models/users/Professor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    header('Content-Type: application/json');

    // Controlla se l'email è valida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato email non valido!";
        exit;
    }
    // Controlla se l'email ha il dominio corretto
    if (!preg_match('/@(studio\.)?unibo\.it$/', $email)) {
        echo "L'email deve essere di dominio @studio.unibo.it o @unibo.it!";
        exit;
    }
    // Controlla se la password è valida
    if (!preg_match("/^[a-zA-Z0-9]*$/", $password) || !preg_match("/[A-Z]/", $password)) {
        echo "La password può contenere solo lettere e numeri e deve avere almeno una lettera maiuscola!";
        exit;
    }

    // Cripta la password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if($_POST['role'] == 'student'){
        Student::signin($_POST['name'], $_POST['surname'], $email, $_POST['matricola'], $_POST['registration_year'], $_POST['phone'], $password_hash);
    }
    else if ($_POST['role'] == 'professor') {
        Professor::signin($_POST['name'], $_POST['surname'], $email, $_POST['course'], $_POST['department'], $_POST['phone'], $password_hash);
    }
}
