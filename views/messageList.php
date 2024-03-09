<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Lista Messaggi</title>
    <link rel="shortcut icon" href="image/Seal_Bologna.svg.png" type="image/x-icon">
    <!-- Qui puoi inserire eventuali stili CSS o link a fogli di stile esterni -->
</head>
<body>
<h1>Messaggi Inviati</h1>

<?php if (isset($messages) && !empty($messages)): ?>
    <ul>
        <?php foreach ($messages as $message): ?>
            <li>
                <h2><?php echo htmlspecialchars($message->getTitle()); ?></h2>
                <p><?php echo htmlspecialchars($message->getText()); ?></p>
                <small>Inviato il: <?php echo htmlspecialchars($message->getDate()); ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nessun messaggio inviato.</p>
<?php endif; ?>
</body>
</html>
