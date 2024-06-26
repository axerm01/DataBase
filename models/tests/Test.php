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
            throw new Exception( "Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            throw new Exception  ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public static function saveTestData($title, $showAnswers, $professorEmail, $image){

        global $con;
        $q = 'CALL CreateTest(?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sis', $title, $showAnswers, $professorEmail);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $con->query("SELECT @lastID as lastID");
        $row = $result->fetch_assoc();
        $id = $row['lastID'];
        $stmt->close();

        if($image != null) {
            if (getimagesize($image['tmp_name'])) {
                $imageBlob = file_get_contents($image['tmp_name']);
                $stmt = $con->prepare("CALL AddToGallery(?, ?)");
                if ($stmt === false) {
                    throw new Exception ("Errore nella preparazione della query: " . $con->error);
                }
                $null = null;
                $stmt->bind_param('ib', $id, $null);
                $stmt->send_long_data(1, $imageBlob); // invia i dati BLOB
                if (!$stmt->execute()) {
                    throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
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
            throw new Exception ("Connessione fallita: " . $con->connect_error);
        }
        try {
            $con->begin_transaction();
            $stmt = $con->prepare("CALL CreateTT(?, ?)");
            foreach ($list as $tableId) {
                $stmt->bind_param("ii", $tableId, $testId);
                $stmt->execute();
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            echo "Errore: " . $e->getMessage();
        }
        $stmt->close();
        logMongo('Tabelle '.json_encode($list).' collegate a test '.$testId);
    }

    public static function getAllTests(){
        global $con;
        $q = 'CALL ViewAllDBTests()';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public static function getProfTests($prof_email){
        global $con;
        $q = 'CALL ViewAllTests(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $prof_email );
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();

        return $data;
    }

    public static function getTestContent($testId){
        return StudentTest::start($testId);
    }

    public static function getTestImage($testId){
        global $con;
        $q = 'CALL ViewPhoto(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId );
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $row['Foto'] = base64_encode($row['Foto']);
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public static function getTestViewResponses($testId) {
        global $con;
        $q = 'CALL CanViewResponses(?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('i', $testId );
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public static function updateVisualizzaRisposte($testId, $condition) {
        global $con;

        $stmt = $con->prepare("CALL ShowTestAnswers(?,?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('ii', $testId, $condition);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        return "Aggiornato Visualizza Risoste";
    }

    public static function deleteTableTestLink($list, $testId){
        global $con;
        try {
            $con->begin_transaction();
            $stmt = $con->prepare("CALL RemoveAllTT(?,?)");
            foreach ($list as $tableId) {
                $stmt->bind_param("ii",$tableId, $testId);
                $stmt->execute();
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
        $stmt->close();
        return "deleteTableTestLink Ok";
    }

    public static function checkIfTestNameExists($nome) {
        global $con;

        $stmt = $con->prepare("CALL CheckIfTestNameExists(?, @result)");
        if ($stmt === false) {
            throw new Exception("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('s', $nome);
        if (!$stmt->execute()) {
            throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $result = $con->query("SELECT @result AS result")->fetch_assoc();

        $stmt->close();
        return (bool) $result['result'];
    }

    // Non usato
    public static function updateTestTitle($testId, $title) {
        global $con;

        $stmt = $con->prepare("CALL UpdateTestTitle(?,?)");
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('si', $testId,$title);

        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        logMongo('Titolo Test '.$testId.' aggiornato a '.$title);
        return "Aggiornamento del titolo completato con successo.";
    }




}