<?php
include('../../controllers/utils/connect.php');

class MultipleChoiceQuestion extends Question {
    private $difficulty;
    private $numAnswers;
    private $answers; // Lista di oggetti Answer


    public function __construct($description, $difficulty, $numAnswers) {
        parent::__construct($description);
        $this->difficulty = $difficulty;
        $this->numAnswers = $numAnswers;
        $this->answers = [];
    }

    public function insertOnDB()
    {
        global $con;
        $q = 'CALL CreateSceltaMultipla(?,?,?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('siiis', $this->description,$this->ID, $this->IDTest, $this->numAnswers, $this->difficulty);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
        $stmt->close();

        foreach ($this->answers as $answer) {
            $answer->setIDTest($this->IDTest);
            $answer->insertOnDB();
        }
    }

    public static function getQuestion($id, $testId) {
        global $con; // Assicurati che $con sia la tua connessione al database

        $query = "SELECT * FROM scelta_multipla WHERE ID = ? AND IDTest = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $id, $testId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;  // Aggiunge ogni riga all'array $data
            }
        }
        else {
            return $data = null;
        }
        $stmt->close();

        $query = "SELECT * FROM scelta WHERE IDScMult = ? AND IDTest = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $id, $testId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;  // Aggiunge ogni riga all'array $data
            }
        }

        return $data;
    }

    // Getter e setter per description, difficulty, numAnswers
    public function getDescription() {
        return $this->description;
    }

    public function getDifficulty() {
        return $this->difficulty;
    }

    public function getNumAnswers() {
        return $this->numAnswers;
    }

    public function getAnswers() {
        return $this->answers;
    }


    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    public function setNumAnswers($numAnswers) {
        $this->numAnswers = $numAnswers;
    }

    public function setAnswers($answers) {
        $this->answers = $answers;
    }


    public function addAnswer(Answer $answer) {
        $this->answers[] = $answer;
    }

    public function removeAnswer($answerID) {
        foreach ($this->answers as $key => $answer) {
            if ($answer->getID() == $answerID) {
                unset($this->answers[$key]);
                $this->answers = array_values($this->answers); // Reindirizza l'array per mantenere l'ordine
                return true;
            }
        }
        return false;
    }

    public function getAnswerByID($ID) {
        foreach ($this->answers as $answer) {
            if ($answer->getID() === $ID) {
                return $answer;
            }
        }
        return null; // Nessuna risposta trovata con l'ID specificato
    }
}


?>

