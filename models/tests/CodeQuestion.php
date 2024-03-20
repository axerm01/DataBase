<?php
include('../../controllers/utils/connect.php');


class CodeQuestion extends Question {
    private $output;

    public function __construct($text, $output) {
        parent::__construct($text);
        $this->output = $output;
    }

    public static function getQuestion($id, $testId) {
        global $con; // Assicurati che $con sia la tua connessione al database

        $query = "SELECT * FROM codice WHERE ID = ? AND IDTest = ?";
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
            $data = null;
        }

        $stmt->close();
        return $data;
    }

    public function insertOnDB()
    {
        global $con;
        $q = 'CALL CreateCodice(?,?,?);';
        $stmt = $con->prepare($q);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $con->error);
        }
        $stmt->bind_param('iis', $this->IDTest, $this->ID, $this->output);
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }

        $stmt->close();
    }


    // Getter e setter per output
    public function getOutput() {
        return $this->output;
    }

    public function setOutput($output) {
        $this->output = $output;
    }
}


?>