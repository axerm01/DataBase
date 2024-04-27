<?php
include('../../controllers/utils/connect.php');
include_once ('CodeQuestion.php');
include_once ('MultipleChoiceQuestion.php');
include_once ('Test.php');
include_once ('../../models/relational/Table.php');
include_once ('../../models/relational/Reference.php');

class StudentTest {
    const OPEN = 'Aperto';
    const IN_PROGRESS = 'InCompletamento';
    const CLOSE = 'Concluso';

    public static function getTests($stdEmail, $filter) //Filter può essere Open, InProgress, Close, All
    {
        global $con;
        if($filter == 'All'){
            $q = "CALL ViewAllSvolgimento(?);";
            $stmt = $con->prepare($q);
            if ($stmt === false) {
                die("Errore nella preparazione della query: " . $con->error);
            }
            $stmt->bind_param('s', $stdEmail);
            if (!$stmt->execute()) {
                die("Errore nell'esecuzione della query: " . $stmt->error);
            }
        }
        else{
            $q = "CALL ViewSvolgimentoByStatus(?,?);";
            $stmt = $con->prepare($q);
            if ($stmt === false) {
                die("Errore nella preparazione della query: " . $con->error);
            }
            $stmt->bind_param('ss', $filter,$stdEmail);
            if (!$stmt->execute()) {
                die("Errore nell'esecuzione della query: " . $stmt->error);
            }
        }

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Aggiunge ogni riga all'array $data
        }
        $stmt->close();

        return $data;
    }
    public static function getSingleTest($idTest, $stdEmail)
    {
        global $con;
        $q = "CALL ViewSvolgimento(?,?);";
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('is', $idTest, $stdEmail);
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
    public static function start($testId) {
        $codeQuestions = CodeQuestion::getTestQuestions($testId);
        $mcQuestions = MultipleChoiceQuestion::getTestQuestions($testId);

        $questions = array_merge($codeQuestions, $mcQuestions);

        // Ordina l'array combinato in base all'ID
        usort($questions, function($a, $b) {
            return $a['ID'] - $b['ID'];
        });

        $TT = Table::getTestTables($testId);
        $tableIDs = [];
        $tables = [];
        foreach ($TT as $index => $table) {
            if (isset($table['IDTabella'])) {
                $tables[$index] = Table::getTableHeader($table['IDTabella']);
                $tables[$index]['columns'] = Column::getTableColumns($table['IDTabella']);
                $tables[$index]['content'] = Table::getTableContent($table['IDTabella']);
                $tableIDs[] = $table['IDTabella'];
            }
        }

        $references = Reference::getReferencesByTableIds($tableIDs);
        $image = Test::getTestImage($testId);

        $test = [];
        $test['image'] = $image;
        $test['questions'] = $questions;
        $test['tables'] = $tables;
        $test['references'] = $references;

        return $test;
    }
    public static function resume($testId, $stdEmail) {

        $codeQuestions = CodeQuestion::getTestQuestions($testId);
        $mcQuestions = MultipleChoiceQuestion::getTestQuestions($testId);

        $codeResponse = StudentAnswer::getTestCodeAnswers($testId, $stdEmail);
        $mcResponse = StudentAnswer::getTestMCAnswers($testId, $stdEmail);

        $questions = array_merge($codeQuestions, $mcQuestions);
        // Ordina l'array combinato in base all'ID
        usort($questions, function($a, $b) {
            return $a['ID'] - $b['ID'];
        });

        $responses = array_merge($codeResponse, $mcResponse);
        // Ordina l'array combinato in base all'ID
        usort($responses, function($a, $b) {
            return $a['IDDomanda'] - $b['IDDomanda'];
        });


        $TT = Table::getTestTables($testId);
        $tableIDs = [];
        $tables = [];
        foreach ($TT as $index => $table) {
            if (isset($table['IDTabella'])) {
                $tables[$index] = Table::getTableHeader($table['IDTabella']);
                $tables[$index]['columns'] = Column::getTableColumns($table['IDTabella']);
                $tables[$index]['content'] = Table::getTableContent($table['IDTabella']);
                $tableIDs[] = $table['IDTabella'];
            }
        }

        $references = Reference::getReferencesByTableIds($tableIDs);
        $image = Test::getTestImage($testId);

        $test = [];
        $test['image'] = $image;
        $test['questions'] = $questions;
        $test['responses'] = $responses;
        $test['tables'] = $tables;
        $test['references'] = $references;

        return $test;
    }
    public static function close($testId, $stdEmail) {
        global $con; // Assumi che $con sia la tua connessione al database
        $currentTime = date('Y-m-d H:i:s'); // Ottieni il timestamp corrente

            // Il record esiste, aggiorna DataUltimaRisposta
            $updateQuery = "UPDATE Svolgimento SET DataUltimaRisposta = ?, Stato = ? WHERE MailStudente = ? AND IDTest = ?";
            $updateStmt = $con->prepare($updateQuery);
            $status = self::CLOSE;
            $updateStmt->bind_param('sssi', $currentTime,$status, $stdEmail, $testId);
            $updateStmt->execute();
            return 'closed correctly';
    }
    public static function saveStudentTestData($testId, $email) {
        global $con;
        $q = 'CALL CreateSvolgimento(?,?,?);';
        $stmt = $con->prepare($q);
        $response = "save ok";
        if ($stmt === false) {
            $response = $con->error;
            //die("Errore nella preparazione della query: " . $con->error);
        }
        $stato = self::OPEN;
        $stmt->bind_param('ssi', $email, $stato, $testId);
        if (!$stmt->execute()) {
            $response = $stmt->error;
            //die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        return $response;
    }

    public static function setFirstResponseDate($testId, $email, $date) {
        global $con;
        $q = 'CALL UpdateInizioSvolgimento(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iss', $testId, $email, $date);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        return 'updated correctly, ';
    }

    public static function setLastResponseDate($testId, $email, $date) {
        global $con;
        $q = 'CALL UpdateFineSvolgimento(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iss', $testId, $email, $date);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        return 'updated correctly, ';
    }

}