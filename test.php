<?php
require 'vendor/autoload.php'; // Assicurati di aver installato la libreria tramite Composer

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->nomeDatabase->nomeCollezione;

$json = [
    'nome' => 'Carlo',
    'anni' => 55
];
$message = "Log: ".json_encode($json);
$insertOneResult = $collection->insertOne(['message' => $message]);

echo "Documento inserito con ID: " . $insertOneResult->getInsertedId();
?>
