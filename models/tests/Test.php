<?php
include('../../controllers/utils/connect.php');

class Test {
    private $id;
    private $title;
    private $creationDate;
    private $showAnswers;
    private $professorEmail;
    private $photo;
    private $questions; // Lista di oggetti Question
    private $tables;  // Lista di oggetti Table
    private $references;

    public function __construct($title, $creationDate, $showAnswers, $professorEmail) {
        $this->title = $title;
        $this->creationDate = $creationDate;
        $this->showAnswers = (int) $showAnswers;
        $this->professorEmail = $professorEmail;
        $this->questions = [];
        $this->tables = [];
        $this->references = [];
    }

    public function insertOnDB(){

        global $con;
        $q = 'CALL CreateTest(?,?,?,@lastID);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('sis', $this->title, $this->showAnswers, $this->professorEmail);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $result = $con->query("SELECT @lastID as lastID");
        $row = $result->fetch_assoc();
        $this->id = $row['lastID'];
        $stmt->close();

        foreach ($this->references as $reference) {
            $reference->insertOnDB();
        }

        foreach ($this->questions as $question) {
            $question->setIDTest($this->id);
            $question->insertOnDB();
        }
    }

    public function linkTablesToTest($list){
        global $con;
        if ($con->connect_error) {
            die("Connessione fallita: " . $con->connect_error);
        }

        try {
            // Inizia la transazione
            $con->begin_transaction();

            // Prepara lo statement SQL
            $stmt = $con->prepare("INSERT INTO TT (IDTabella, IDTest) VALUES (?, ?)");

            foreach ($list as $tableId) {
                // Bind dei parametri e esecuzione dello statement
                $stmt->bind_param("ii", $tableId, $this->id);
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

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getShowAnswers() {
        return $this->showAnswers;
    }

    public function getProfessorEmail() {
        return $this->professorEmail;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function getTables() {
        return $this->tables;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setShowAnswers($showAnswers) {
        $this->showAnswers = $showAnswers;
    }

    public function setProfessorEmail($professorEmail) {
        $this->professorEmail = $professorEmail;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function setTables($tables) {
        $this->tables = $tables;
    }


    public function addQuestion(Question $question) {
        $this->questions[] = $question;
    }

    public function removeQuestion($questionID) {
        foreach ($this->questions as $key => $question) {
            if ($question->getID() === $questionID) {
                unset($this->questions[$key]);
                $this->questions = array_values($this->questions);
                return true;
            }
        }
        return false;
    }

    public function getQuestionByID($questionID) {
        foreach ($this->questions as $question) {
            if ($question->getID() === $questionID) {
                return $question;
            }
        }
        return null;
    }



    public function addTable($table) {
        $this->tables[] = $table;
    }

    public function removeTable($tableID) {
        foreach ($this->tables as $key => $table) {
            if ($table === $tableID) {
                unset($this->tables[$key]);
                $this->tables = array_values($this->tables);
                return true;
            }
        }
        return false;
    }

    public function getTableByID($tableID) {
        foreach ($this->tables as $table) {
            if ($table === $tableID) {
                return $table;
            }
        }
        return null;
    }

    public function addRef(Reference $reference) {
        $this->references[] = $reference;
    }

    public function removeRef($tab1, $tab2, $att1, $att2) {
        foreach ($this->references as $key => $reference) {
            if (($reference->getTab1() === $tab1)&&($reference->getTab2() === $tab2)&&($reference->getAtt1() === $att1)&&($reference->getAtt2() === $att2)) {
                unset($this->references[$key]);
                $this->references = array_values($this->references);
                return true;
            }
        }
        return false;
    }

    public function getRef($tab1, $tab2, $att1, $att2) {
        foreach ($this->references as $reference) {
            if (($reference->getTab1() === $tab1)&&($reference->getTab2() === $tab2)&&($reference->getAtt1() === $att1)&&($reference->getAtt2() === $att2)) {
                return $reference;
            }
        }
        return null;
    }
}