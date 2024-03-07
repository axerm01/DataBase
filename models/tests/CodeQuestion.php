<?php
include('../../controllers/utils/connect.php');


class CodeQuestion extends Question {
    private $output;

    public function __construct($output) {
        $this->output = $output;
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