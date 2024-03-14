<?php
include 'connesione.php';

// Esegui una query di esempio
$sql = "SELECT * FROM paperino";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Stampa le intestazioni delle colonne
    echo "<table border='1'><tr>";
    $columns = $result->fetch_fields(); // Ottiene i dettagli dei campi risultato
    foreach ($columns as $column) {
        echo "<th>" . $column->name . "</th>";
    }
    echo "</tr>";

    // Stampa i dati di ogni riga
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<td>" . $row[$column->name] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 risultati";
}

$conn->close();