<?php
include_once('../../controllers/utils/connect.php');
include_once '../../models/tests/StudentTest.php';

class Test {
    public static function testQuery($q){
        global $con;

        $restrictedKeywords = [
            'INSERT', 'DROP', 'DELETE', 'UPDATE', 'CALL',
            'Attributo', 'Codice', 'Docente', 'Galleria', 'Messaggio_docente',
            'Messaggio_studente', 'Referenze', 'Risposta_codice', 'Risposta_scelta',
            'Scelta', 'Scelta_multipla', 'Studente', 'Svolgimento', 'Tabella', 'Test',
            'Test_tabella', 'Classificaquesiti', 'Classificarispostecorrette', 'Classificatestcompletati'
        ];

        foreach ($restrictedKeywords as $keyword) {
            if (stripos($q, $keyword) !== false) {
                return "Forbidden, Query contenente termini non consentiti: $keyword";
            }
        }

        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return "Errore nella preparazione della query: " . $con->error;
        }
        if (!$stmt->execute()) {
            return  "Errore nell'esecuzione della query: " . $stmt->error;
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Aggiunge ogni riga all'array $data
        }
        $stmt->close();
        return $data;
    }

    public static function saveTestData($title, $showAnswers, $professorEmail, $image){

        global $con;
        $q = 'CALL CreateTest(?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sis', $title, $showAnswers, $professorEmail);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $con->query("SELECT @lastID as lastID");
        $row = $result->fetch_assoc();
        $id = $row['lastID'];
        $stmt->close();

        if($image != null) {
            if (getimagesize($image['tmp_name'])) {
                // Leggi il contenuto del file
                $imageBlob = file_get_contents($image['tmp_name']);
                $stmt = $con->prepare("CALL AddToGalleria(?, ?)");
                if ($stmt === false) {
                    die("Errore nella preparazione della query: " . $con->error);
                }

                $null = null; // mysqli richiede questo per i blob
                $stmt->bind_param('ib', $id, $null);
                $stmt->send_long_data(1, $imageBlob); // invia i dati BLOB
                if (!$stmt->execute()) {
                    die("Errore nell'esecuzione della query: " . $stmt->error);
                }
                $stmt->close();
            }
        }
        logMongo('Test salvato: '.$title);
        return $id;
    }

    public static function linkTablesToTest($list, $testId){
        global $con;
        if ($con->connect_error) {
            die("Connessione fallita: " . $con->connect_error);
        }
        try {
            // Inizia la transazione
            $con->begin_transaction();
            // Prepara lo statement SQL
            $stmt = $con->prepare("INSERT INTO TEST_TABELLA (IDTabella, IDTest) VALUES (?, ?)");
            foreach ($list as $tableId) {
                // Bind dei parametri e esecuzione dello statement
                $stmt->bind_param("ii", $tableId, $testId);
                $stmt->execute();
            }
            // Committa la transazione
            $con->commit();
        } catch (Exception $e) {
            // Qualcosa è andato storto: esegui il rollback
            $con->rollback();
            echo "Errore: " . $e->getMessage();
        }
        // Chiudi lo statement e la connessione
        $stmt->close();
        logMongo('Tabelle '.json_encode($list).' collegate a test '.$testId);
    }

    public static function getAllTests(){
        global $con;
        $q = 'SELECT * FROM TEST';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Aggiunge ogni riga all'array $data
        }
        $stmt->close();

        return $data;
    }

    public static function getProfTests($prof_email){
        global $con;
        $q = 'CALL ViewAllTest(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $prof_email );
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Aggiunge ogni riga all'array $data
        }
        $stmt->close();

        return $data;
    }

    public static function getTestContent($testId){
        return StudentTest::start($testId);
    }

    public static function getTestImage($testId){
        global $con;
        $q = 'CALL ViewFoto(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            return ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId );
        if (!$stmt->execute()) {
            return ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            // Per i dati BLOB, considera la necessità di elaborarli prima di aggiungerli all'array $data
            $row['Foto'] = base64_encode($row['Foto']); // Codifica il BLOB in base64 se intendi inviarlo al client, ad esempio per visualizzarlo come immagine
            $data[] = $row; // Aggiunge ogni riga all'array $data
        }

        $stmt->close();

        return $data;
    }

    public static function updateTestTitle($testId, $title) {
        global $con; // Assumi che $con sia la tua connessione al database (mysqli)

        // Preparazione della query SQL
        $stmt = $con->prepare("CALL UpdateTestTitle(?,?)");
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('si', $testId,$title);

        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        logMongo('Titolo Test '.$testId.' aggiornato a '.$title);
        return "Aggiornamento del titolo completato con successo.";
    }

    public static function updateVisualizzaRisposte($testId) {
        global $con; // Assumi che $con sia la tua connessione al database (mysqli)

        // Preparazione della query SQL
        $stmt = $con->prepare("CALL UpdateVisualizzaRisposteTest(?)");

        // Verifica se la preparazione della query ha avuto successo
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }

        // Associa i parametri alla query preparata
        $stmt->bind_param('i', $testId);

        // Esegui la query
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        // Chiusura dello statement
        $stmt->close();

        return "Aggiornato Visualizza Risoste a True";
    }

    public static function deleteTableTestLink($list, $testId){
        global $con;
        try {
            // Inizia la transazione
            $con->begin_transaction();
            // Prepara lo statement SQL
            $stmt = $con->prepare("DELETE FROM TABELLE_TEST WHERE IDTest = ? AND IDTabella = ?");
            foreach ($list as $tableId) {
                // Bind dei parametri e esecuzione dello statement
                $stmt->bind_param("ii", $testId, $tableId);
                $stmt->execute();
            }
            // Committa la transazione
            $con->commit();
        } catch (Exception $e) {
            // Qualcosa è andato storto: esegui il rollback
            $con->rollback();
            echo "Errore: " . $e->getMessage();
        }
        // Chiudi lo statement e la connessione
        $stmt->close();
    }

    public static function checkIfTestNameExists($nome, $email) {
        global $con;

        // Prepara la chiamata alla stored procedure
        $stmt = $con->prepare("CALL CheckIfTestNameExists(?,?, @result)");
        if ($stmt === false) {
            // Gestisci l'errore di preparazione della query
            throw new Exception("Errore nella preparazione della query: " . $con->error);
        }

        // Lega i parametri
        $stmt->bind_param('ss', $nome, $email);

        // Esegui la stored procedure
        if (!$stmt->execute()) {
            // Gestisci l'errore di esecuzione della query
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }

        // Recupera il risultato
        $result = $con->query("SELECT @result AS result")->fetch_assoc();

        $stmt->close();

        // Restituisce il risultato booleano
        return (bool) $result['result'];
    }




}