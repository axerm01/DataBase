<?php
session_start();
include('../utils/connect.php');


$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'];
//$uri = explode('?', $_SERVER['REQUEST_URI'], 2);
//$endpoint = $uri[0];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $data = 'no data';
        switch ($endpoint) {

            case 'test_query': //restituisce il risultato della query inserita dal docente per trovare la risposta di una code question
            $query = filter_input(INPUT_GET, 'query');
            $data = testQuery($query);
                break;

            case 'get_tests': // GET di tutti i test di un certo professore la cui mail è passata da FE
                $prof_email = filter_input(INPUT_GET, 'prof_email');
                $data = getAllTests($prof_email);
                break;

            case 'get_tables': // GET delle tabelle create da un docente, restituisce id tabella e nome
                $data = getAllTables($_SESSION['email']);
                break;

            case 'get_table_columns': // GET delle colonne di una tabella indicata
                $id = filter_input(INPUT_GET, 'tableId');
                $data = getTableColumns($id);
                break;

            case 'get_full_table': // GET del contenuto della tabella
                $id = filter_input(INPUT_GET, 'tableId');
                $columns = getTableColumns($id);
                $content = getTableContent($id);

                $data = array_merge(array($columns), $content);
                break;
        }

        echo json_encode($data);  // Converte l'array $data in JSON e lo invia
        break;

    case 'POST':
        newTest();
        break;

    case 'PUT':

        break;

    case 'DELETE':

        break;
}

//Inserisce a DB un nuovo test creato dal docente
function newTest() {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        // Ricevi il contenuto grezzo
        $content = trim(file_get_contents("php://input"));

        // Decodifica il JSON ricevuto
        $decodedData = json_decode($content, true);

        $creationDate = date('Y-m-d H:i:s');
        $test = new Test($decodedData['title'], $creationDate, $decodedData['show_answers'], $_SESSION['email']);
        $qID = 0;

        // Sezione dedicata al salvataggio delle tabelle relative al Test
        foreach ($decodedData['tables'] as $tableID) {
            $test->addTable($tableID);
        }
        $test->linkTablesToTest($decodedData['tables']);

        // Sezione dedicata al salvataggio delle referenze relative al Test
        foreach ($decodedData['references'] as $referenceData) {
            $reference = new Reference($referenceData['tab1'], $referenceData['tab2'], $referenceData['att1'], $referenceData['att2']);
            $test->addRef($reference);
        }

        // Sezione dedicata al salvataggio delle domande relative al Test
        foreach ($decodedData['questions'] as $questionData) {
            $qID = $qID + 1;
            if ($questionData['type'] == 'code') {
                $question = new CodeQuestion($questionData['text'], $questionData['output']);
                $question->setID($qID);
            } else if ($questionData['type'] == 'mc') {
                $question = new MultipleChoiceQuestion($questionData['text'], $questionData['diff'], count($questionData['answers']));
                $question->setID($qID);
                $IDAnswer = 0;
                foreach ($decodedData['answers'] as $answersData) {
                    $IDAnswer = $IDAnswer + 1;
                    $answer = new Answer($IDAnswer, $qID, $decodedData['text'], $decodedData['isCorrect']);
                    $question->addAnswer($answer);
                }


            }
            $test->addQuestion($question);
        }

        $test->insertOnDB();


    }
}

function getAllTables($profEmail) //restituisce id tabella e nome
{
    global $con;
    $q = 'CALL ViewAllTables(?);';
    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
    $stmt->bind_param('s', $profEmail);
    if (!$stmt->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'IDTabella' => $row['ID'],
            'Nome' => $row['Nome']
        ];
    }
    $stmt->close();

    return $data;

}

function getTableColumns($tableId)
{
    global $con;
    $q = 'CALL ViewAllAttributes(?);';
    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
    $stmt->bind_param('s', $tableId );
    if (!$stmt->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'IDTabella' => $row['IDTabella'],
            'Nome' => $row['Nome']
        ];
    }
    $stmt->close();

    return $data;

}

function testQuery($q){
    global $con;
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

function getAllTests($prof_email){
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

function getTableContent($tableID){
    global $con;

    // Prima, ottieni il nome della tabella in base al suo ID
    $queryNomeTabella = "SELECT Nome FROM tabella WHERE ID = ?";
    $stmt1 = $con->prepare($queryNomeTabella);
    if ($stmt1 === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }

    $stmt1->bind_param('i', $tableID);
    if (!$stmt1->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt1->error);
    }

    $resultNomeTabella = $stmt1->get_result();
    if ($row = $resultNomeTabella->fetch_assoc()) {
        $tableName = $row['Nome'];
    } else {
        die("Nessuna tabella trovata con l'ID specificato");
    }
    $stmt1->close();


    $q = "SELECT * FROM ".$tableName;
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



?>

