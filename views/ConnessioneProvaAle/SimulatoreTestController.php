<?php

// Simula la generazione di una lista di nomi e ID (normalmente questi dati verrebbero dal database)
$listaNomi = array(
    array("id" => 1, "name" => "Mario"),
    array("id" => 2, "name" => "Luigi"),
    array("id" => 3, "name" => "Peach")
);

// Imposta l'header per la risposta JSON
header('Content-Type: application/json');

// Converti l'array PHP in una stringa JSON e stampala
echo json_encode($listaNomi);

// Opzionalmente, salva i dati in un file JSON sul server
file_put_contents('nomi.json', json_encode($listaNomi));
?>
