<?php
session_start();

// Assicurati che l'utente sia loggato e che il ruolo sia impostato
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php'); // Redirigi all'accesso se non loggato
    exit;
}

$role = $_SESSION['role'];
$actionSendMessage = ($role === 'student') ? 'new_student_message' : 'new_professor_message';
$actionGetMessages = ($role === 'student') ? 'get_student_messages' : 'get_professor_messages';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <!-- Aggiungi qui eventuali link a CSS o JavaScript -->
</head>
<body>

<h1>Benvenuto nella Home Page <?php echo $_SESSION['email']; ?></h1>

<!-- Form per inviare un nuovo messaggio -->
<form action="../controllers/MessageController.php" method="post">
    <input type="hidden" name="action" value="<?php echo $actionSendMessage; ?>">
    Test ID: <input type="text" name="testId"><br>
    Titolo: <input type="text" name="title"><br>
    Testo: <textarea name="text"></textarea><br>
    <input type="submit" value="Invia Messaggio">
</form>

<!-- Visualizzazione dei messaggi inviati -->
<form action="../controllers/MessageController.php" method="get">
    <input type="hidden" name="action" value="<?php echo $actionGetMessages; ?>">
    <input type="submit" value="Visualizza Messaggi inviati">
</form>

<a href="../views/createTable.html">Crea nuova tabella</a>

</body>
</html>

