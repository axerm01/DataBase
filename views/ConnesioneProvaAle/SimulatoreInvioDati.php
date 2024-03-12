<?php
// Assumi che l'ID (e opzionalmente il nome) della tabella sia passato tramite POST
$tabellaId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$tabellaName = isset($_POST['name']) ? $_POST['name'] : '';

// Simula la generazione di dati della tabella in base all'ID ricevuto
// Nota: nella pratica reale, qui potresti avere una query al database basata su $tabellaId
$datiTabella = [];
switch ($tabellaId) {
    case 1:
        $datiTabella = [
            ["id" => 1, "name" => "Elemento di Mario 1"],
            ["id" => 2, "name" => "Elemento di Mario 2"],
            ["id" => 3, "name" => "Elemento di Mario 3"],
        ];
        break;
    case 2:
        $datiTabella = [
            ["id" => 1, "name" => "Elemento di Luigi 1"],
            ["id" => 2, "name" => "Elemento di Luigi 2"],
            ["id" => 3, "name" => "Elemento di Luigi 3"],
        ];
        break;
    case 3:
        $datiTabella = [
            ["id" => 1, "name" => "Elemento di Peach 1"],
            ["id" => 2, "name" => "Elemento di Peach 2"],
            ["id" => 3, "name" => "Elemento di Peach 3"],
        ];
        break;
    default:
        $datiTabella = [
            ["id" => 0, "name" => "Nessuna tabella selezionata o tabella non valida"]
        ];
}

// Imposta l'header per la risposta JSON
header('Content-Type: application/json');

// Converti i dati della tabella in una stringa JSON e stampala
echo json_encode($datiTabella);
?>
