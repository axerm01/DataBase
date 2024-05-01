<?php
include_once('../../controllers/utils/connect.php');
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
            $q = "CALL ViewAllStudentTest(?);";
            $stmt = $con->prepare($q);
            if ($stmt === false) {
                throw new Exception ("Errore nella preparazione della query: " . $con->error);
            }
            $stmt->bind_param('s', $stdEmail);
            if (!$stmt->execute()) {
                throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
            }
        }
        else{
            $q = "CALL ViewStudentTestByStatus(?,?);";
            $stmt = $con->prepare($q);
            if ($stmt === false) {
                throw new Exception ("Errore nella preparazione della query: " . $con->error);
            }
            $stmt->bind_param('ss', $filter,$stdEmail);
            if (!$stmt->execute()) {
                throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
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
        $q = "CALL ViewStudentTest(?,?);";
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('is', $idTest, $stdEmail);
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
    public static function start($testId) {
        $codeQuestions = CodeQuestion::getTestQuestions($testId);
        $mcQuestions = MultipleChoiceQuestion::getTestQuestions($testId);

        $questions = array_merge($codeQuestions, $mcQuestions);

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
        $visualizzaRisposte = Test::getTestViewResponses($testId);

        $test = [];
        $test['image'] = $image;
        $test['questions'] = $questions;
        $test['tables'] = $tables;
        $test['references'] = $references;
        $test['canViewResponses'] = $visualizzaRisposte;

        return $test;
    }
    public static function resume($testId, $stdEmail) {

        $codeQuestions = CodeQuestion::getTestQuestions($testId);
        $mcQuestions = MultipleChoiceQuestion::getTestQuestions($testId);

        $codeResponse = StudentAnswer::getTestCodeAnswers($testId, $stdEmail);
        $mcResponse = StudentAnswer::getTestMCAnswers($testId, $stdEmail);

        $questions = array_merge($codeQuestions, $mcQuestions);
        usort($questions, function($a, $b) {
            return $a['ID'] - $b['ID'];
        });

        $responses = array_merge($codeResponse, $mcResponse);
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
    public static function saveStudentTestData($testId, $email) {
        global $con;
        $q = 'CALL CreateStudentTest(?,?,?);';
        $stmt = $con->prepare($q);
        $response = "save ok";
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stato = self::OPEN;
        $stmt->bind_param('ssi', $email, $stato, $testId);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
        logMongo('Svolgimento inserito per test '.$testId.' da '.$email);
        return $response;
    }

    public static function setFirstResponseDate($testId, $email, $date) {
        global $con;
        $q = 'CALL UpdateStudentTestStart(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iss', $testId, $email, $date);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Inserimento data prima risposta per Test '.$testId.' da '.$email);
        return 'updated correctly, ';
    }

    public static function setLastResponseDate($testId, $email, $date) {
        global $con;
        $q = 'CALL UpdateStudentTestEnd(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            throw new Exception ("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iss', $testId, $email, $date);
        if (!$stmt->execute()) {
            throw new Exception ("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();
        logMongo('Inserimento data ultima risposta per Test '.$testId.' da '.$email);
        return 'updated correctly, ';
    }

}