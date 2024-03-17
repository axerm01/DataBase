<?php
session_start();

// Assicurati che l'utente sia loggato e che il ruolo sia impostato
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../views/login.html'); // Redirigi all'accesso se non loggato
    exit;
}

$role = $_SESSION['role'];
$actionSendMessage = ($role === 'student') ? 'new_student_message' : 'new_professor_message';
$actionGetMessages = ($role === 'student') ? 'get_student_messages' : 'get_professor_messages';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prof's_message_home</title>
    <!-- Aggiungi qui eventuali link a CSS o JavaScript -->
    <link rel="shortcut icon" href="image/Seal_Bologna.svg.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
         /* Styles for textarea */
        .write_in_textarea {
            width: calc(100% - 65px); /* Make the textarea occupy the full width of its container */
            height: 130px; /* Set the height as needed */
            padding: 10px; /* Add padding for better visual appeal */
            margin-top: 15px; /* Add some spacing above the textarea */
            border: 1px solid #ccc; /* Add a border for clarity */
            border-radius: 5px; /* Add rounded corners for a softer look */
            box-sizing: border-box; /* Include padding and border in the width calculation */
            font-size: 16px; /* Set font size */
            color: #333; /* Set text color */
        }
        /* Stile per il pulsante di registrazione */
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<header>
    <h1>Messaggi di <?php echo $_SESSION['email']; ?></h1>
</header>
<div class="home-container">
    <!-- Form per inviare un nuovo messaggio -->
    <form action="../controllers/utils/MessageController.php" method="post">
        <input type="hidden" name="action" value="<?php echo $actionSendMessage; ?>">
        <p>Test ID: <input type="text" name="testId"></p>
        <p>Titolo: <input type="text" name="title"></p>
        <p>Testo: </p><textarea class="write_in_textarea"></textarea><br>
        <input type="submit" value="Invia Messaggio">
    </form>

    <!-- Visualizzazione dei messaggi inviati -->
    <form action="../controllers/utils/MessageController.php" method="get">
        <input type="hidden" name="action" value="<?php echo $actionGetMessages; ?>">
        <input type="submit" value="Vedi Messaggi">
    </form>

    <a href="../views/createTable.html">Crea nuova tabella</a>
</div>
<footer>
        <p>Alma Mater Studiorum</p>
        <h3>Università di Bologna</h3>
    </footer>
</body>
</html>

