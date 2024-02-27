<?php
session_start();
// Da richiamare quando si termina l'inserimento di tutte le colonne della tabella

/*if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SESSION['table']))) {
    $table = unserialize($_SESSION['table']);

    //Query DB per creaz tabella

    unset($_SESSION['table']);
}*/

include('../models/relational/Table.php');

if (($_SERVER["REQUEST_METHOD"] == "POST")){
    $table_name = filter_input(INPUT_POST, 'table_name');
    $prof_email = $_SESSION['email'];
    $num_rows = filter_input(INPUT_POST, 'num_rows');

    $table = new Table($table_name, $prof_email, $num_rows);




}

?>

