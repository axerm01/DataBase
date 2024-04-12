<?php
include 'connect.php';

// Controlla se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    header('Content-Type: application/json');

    // Controlla se l'email è valida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato email non valido!";
        exit;
    }
    // Controlla se l'email ha il dominio corretto
    if (!preg_match('/@(studio\.)?unibo\.it$/', $email)) {
        echo "L'email deve essere di dominio @studio.unibo.it o @unibo.it!";
        exit;
    }
    // Controlla se la password è valida
    if (!preg_match("/^[a-zA-Z0-9]*$/", $password) || !preg_match("/[A-Z]/", $password)) {
        echo "La password può contenere solo lettere e numeri e deve avere almeno una lettera maiuscola!";
        exit;
    }

    // Cripta la password - valutare se usare questa funzione.
    //$password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Crea una connessione al database
    global $con;

    // Controlla la connessione
    if ($con->connect_error) {
        die("Connessione fallita: " . $con->connect_error);
    }

    if($_POST['role'] == 'student'){
        $stmt = $con->prepare("CALL CreateStudente (?,?,?,?,?,?,?)");
        //$stmt->bind_param("sssssss", $_POST['name'], $_POST['surname'], $email, $_POST['matricola'], $_POST['registration_year'], $_POST['phone'], $password_hash);
        $stmt->bind_param("sssssss", $_POST['name'], $_POST['surname'], $email, $_POST['matricola'], $_POST['registration_year'], $_POST['phone'], $password);
    }
    else if ($_POST['role'] == 'professor') {
        $stmt = $con->prepare("CALL CreateDocente (?,?,?,?,?,?,?)");
        //$stmt->bind_param("sssssis", $_POST['name'], $_POST['surname'], $email, $_POST['course'], $_POST['department'], $_POST['phone'], $password_hash);
        $stmt->bind_param("sssssss", $_POST['name'], $_POST['surname'], $email, $_POST['course'], $_POST['department'], $_POST['phone'], $password);
    }

    // Pezzo di codice che dovrebe restituire un riscontro
    if ($stmt->execute()) {
            // Invece di restituire JSON, reindirizza a una pagina di successo
            header('Location: ../../views/login.html');
            exit;
    } else {
        $messaggio = "Si è verificato un errore durante la registrazione.";
    }
    // Restituisci il messaggio come risposta JSON
    echo json_encode(["message" => $messaggio]);

    // Chiudi la connessione
    $con->close();
}
?>
