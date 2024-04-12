<?php
// Controllo se il form è stato sottomesso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Raccolta dei dati dal form
    $tableTitle = $_POST['tableTitle'] ?? '';
    $columnName = $_POST['columnName'] ?? '';
    $columnType = $_POST['columnType'] ?? '';
    $isPK = isset($_POST['isPK']) ? true : false;
    $rows = [
        $_POST['row1'] ?? '',
        $_POST['row2'] ?? '',
        $_POST['row3'] ?? '',
    ];

    // Creazione del payload JSON
    $payload = json_encode([
        'title' => $tableTitle,
        'attributes' => [
            'name' => $columnName,
            'type' => $columnType,
            'isPrimaryKey' => $isPK
        ],
        'rows' => $rows
    ]);

    // Invio dei dati al controller tramite cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:63342/AABProject/controllers/table/TableController.php/tables");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        echo "Errore nella richiesta: " . curl_error($ch);
    } else {
        echo "Risposta ricevuta: " . $response;
    }

    curl_close($ch);

    // Ferma l'esecuzione per mostrare la risposta
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inserisci Dati Tabella</title>
</head>
<body>
<form method="post">
    <label for="tableTitle">Titolo Tabella:</label>
    <input type="text" id="tableTitle" name="tableTitle"><br>

    <h3>Colonna</h3>
    <label for="columnName">Nome Colonna:</label>
    <input type="text" id="columnName" name="columnName"><br>
    <label for="columnType">Tipo Colonna:</label>
    <input type="text" id="columnType" name="columnType"><br>
    <label for="isPK">Chiave Primaria?</label>
    <input type="checkbox" id="isPK" name="isPK"><br>

    <h3>Righe</h3>
    <label for="row1">Riga 1:</label>
    <input type="text" id="row1" name="row1"><br>
    <label for="row2">Riga 2:</label>
    <input type="text" id="row2" name="row2"><br>
    <label for="row3">Riga 3:</label>
    <input type="text" id="row3" name="row3"><br>

    <input type="submit" value="Invia">
</form>
</body>
</html>

