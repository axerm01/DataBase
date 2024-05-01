<?php
session_start();
include_once('connect.php');
include_once '../../models/users/Student.php';
include_once '../../models/users/Professor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        if ($role == 'professor') {
            $login = Professor::login($email, $password);
        } else if ($role == 'student') {
            $login = Student::login($email, $password);
        }
    } catch (Exception $e){
        echo "Login Error: ".$e->getMessage();
    }

    if ($login) {
        $_SESSION["loggedin"] = true;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;

        echo "Login successful!";

        if ($role == 'professor') {
            header('Location: ../../views/prof_home.html');
        } else if ($role == 'student') {
            header('Location: ../../views/student_home.html');
        }
    }
    else {
        echo "Invalid Email or Password";
    }
}
