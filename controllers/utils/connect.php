<?php
//Connessione a MySql
    $con = mysqli_connect("localhost","root","Impeto02@","esql");
    if(!$con)
    {
        die("cannot connect to server");
    }

//Connessione a MongoDB
require_once '../../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$mongo = $client->ESQLDB->EventLog;

function logMongo($message) {
    global $mongo;
    $mongo->insertOne(['message' => $message]);
}
