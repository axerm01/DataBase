<?php
session_start();
include('../utils/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET"){ // restituzione tabelle presenti
    global $con;
    $q = "CALL ViewAllTables(?)";
    $stmt = $con->prepare($q);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $con->error);
    }
    $stmt->bind_param('s',$_SESSION['email']);
    if (!$stmt->execute()) {
        die("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $tableNames = [];
    while ($row = $result->fetch_assoc()) {
        $tableNames[] = $row['Nome'];
    }

    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($tableNames);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Verifica se il contenuto ricevuto è JSON
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        // Ricevi il contenuto grezzo
        $content = trim(file_get_contents("php://input"));

        // Decodifica il JSON ricevuto
        $decodedData = json_decode($content, true);

        $creationDate = date('Y-m-d H:i:s');
        $test = new Test($decodedData['title'],$creationDate, $decodedData['show_answers'], $_SESSION['email']);
        $ID = 0;
        foreach ($decodedData['questions'] as $questionData){
            $ID = $ID+1;
            if($questionData['type'] == 'code'){
                $question = new CodeQuestion($questionData['output']);
                $question->setID($ID);
            }
            else if($questionData['type'] == 'mc'){
                $question = new MultipleChoiceQuestion($questionData['description'], $questionData['diff'], count($questionData['answers']));
                $question->setID($ID);
                $IDAnswer = 0;
                foreach ($decodedData['answers'] as $answersData){
                    $IDAnswer = $IDAnswer+1;
                    $answer = new Answer($IDAnswer,$ID,$decodedData['text'],$decodedData['isCorrect']);
                    $question->addAnswer($answer);
                }


            }

            $test->addQuestion($question);
        }

        $test->insertOnDB();


    }
}



?>

