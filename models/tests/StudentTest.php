<?php
include('../../controllers/utils/connect.php');
include ('CodeQuestion.php');
include ('MultipleChoiceQuestion.php');
include ('../../models/relational/Table.php');
include ('../../models/relational/Reference.php');

class StudentTest {
    const OPEN = 'Open';
    const IN_PROGRESS = 'InProgress';
    const CLOSE = 'Close';

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
            $stmt->bind_param('ss', $stdEmail, $filter);
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
                $tables[$index] = Table::getTableData($table['IDTabella']);
                $tables[$index]['columns'] = Column::getTableColumns($table['IDTabella']);
                $tables[$index]['content'] = Table::getTableContent($table['IDTabella']);
                $tableIDs[] = $table['IDTabella'];
            }
        }

        $references = Reference::getReferencesByTableIds($tableIDs);

        $test = [];
        $test['questions'] = $questions;
        $test['tables'] = $tables;
        $test['references'] = $references;

        return $test;
    }
    public static function resume($testId, $stdEmail) {
        global $con; // Assumi che $con sia la tua connessione al database

        $codeQuestions = CodeQuestion::getTestQuestions($testId);
        $mcQuestions = MultipleChoiceQuestion::getTestQuestions($testId);
        $mcAnswers = Answer::getTestMCAnswers($testId);
        foreach ($mcQuestions as $qIndex => $question) {
            // Inizializza l'attributo "answers" come un array vuoto
            $mcQuestions[$qIndex]['answers'] = [];

            // Cerca le risposte corrispondenti in $mcAnswers
            foreach ($mcAnswers as $answer) {
                if ($answer['IDScMult'] == $question['ID']) {
                    // Aggiungi la risposta all'array "answers"
                    $mcQuestions[$qIndex]['answers'][] = [
                        'ID' => $answer['ID'],
                        'Testo' => $answer['Testo'],
                        'IsCorretta' => $answer['IsCorretta']
                    ];
                }
            }
        }

        $codeResponse = StudentAnswer::getTestCodeAnswers($testId, $stdEmail);
        $mcResponse = StudentAnswer::getTestMCAnswers($testId, $stdEmail);
        foreach ($codeQuestions as $qIndex => $question) {
            foreach ($codeResponse as $response) {
                if ($question['ID'] == $response['IDDomanda']) {
                    $codeQuestions[$qIndex]['Risposta'] = [
                        'Risposta' => $response['Risposta'],
                        'Esito' => $response['Esito']
                    ];
                    break; // Interrompe il ciclo interno una volta trovata la corrispondenza
                }
            }
        }
        foreach ($mcQuestions as $qIndex => $question) {
            foreach ($mcResponse as $response) {
                if ($question['ID'] == $response['IDDomanda']) {
                    $codeQuestions[$qIndex]['Risposta'] = [
                        'Risposta' => $response['Risposta'],
                        'Esito' => $response['Esito']
                    ];
                    break; // Interrompe il ciclo interno una volta trovata la corrispondenza
                }
            }
        }

        $questions = array_merge($codeQuestions, $mcQuestions);
        // Ordina l'array combinato in base all'ID
        usort($questions, function($a, $b) {
            return $a['ID'] - $b['ID'];
        });


        $tables = Table::getTestTables($testId);
        $tableIDs =[];
        foreach ($tables as $index => $table) {
            if (isset($table['ID'])) {
                $tables[$index]['columns'] = Column::getTableColumns($table['ID']);
                $tables[$index]['content'] = Table::getTableContent($table['ID']);
                $tableIDs = $table['ID'];
            }
        }

        $references = Reference::getReferencesByTableIds($tableIDs);

        $test = [];
        $test['questions'] = $questions;
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
    public static function saveStudentTestData($dataPrima, $dataUltima, $testId, $email) {
        global $con;
        $q = 'CALL CreateSvolgimento(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stato = self::OPEN;
        $stmt->bind_param('ssssi', $email, $stato, $dataPrima, $dataUltima, $testId);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }
    public static function updateStudentTestData($testId, $email) {
        global $con;
        $q = 'CALL UpdateFineSvolgimento(?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('is', $testId, $email );
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }
    public static function updateStudentTestStatus($testId, $email) {
        global $con;
        $q = 'CALL UpdateStatoSvolgimento(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $status = self::IN_PROGRESS;
        $stmt->bind_param('iss', $testId, $email, $status );
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
    }

}