<?php
include('../../controllers/utils/connect.php');

class Test {

    public static function saveTestData($title, $creationDate, $showAnswers, $professorEmail){

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
        $con->close();
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

    public static function showInfo($testId){
        global $con;
        $q = 'select * from test where ID = ?';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId );
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

    public static function updateTestData($testId, $title, $showAnswers) {
        global $con; // Assumi che $con sia la tua connessione al database (mysqli)

        // Preparazione della query SQL
        $stmt = $con->prepare("UPDATE TEST SET Titolo = ?, VisualizzaRisposte = ? WHERE ID = ?");

        // Verifica se la preparazione della query ha avuto successo
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }

        // Associa i parametri alla query preparata
        $stmt->bind_param('sii', $title, $showAnswers, $testId);

        // Esegui la query
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        // Chiusura dello statement
        $stmt->close();

        return "Aggiornamento completato con successo.";
    }

    public static function deleteTableTestLink($IDtest, $IDTabella) {
        global $con; // Assumi che $con sia la tua connessione al database (mysqli)

        // Preparazione della query SQL
        $stmt = $con->prepare("DELETE FROM TABELLE_TEST WHERE IDTest = ? AND IDTabella = ?");

        // Verifica se la preparazione della query ha avuto successo
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }

        // Associa i parametri alla query preparata
        $stmt->bind_param('ii', $IDtest, $IDTabella);

        // Esegui la query
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        // Chiusura dello statement
        $stmt->close();

        return "Record eliminati con successo.";
    }


}