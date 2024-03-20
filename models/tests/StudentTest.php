<?php

class StudentTest {
    private $studentEmail;
    private $testID;
    private $status; // Can be 'Open', 'InProgress', 'Close'
    private $firstResponseDate;
    private $lastResponseDate;

    const OPEN = 'Open';
    const IN_PROGRESS = 'InProgress';
    const CLOSE = 'Close';

    public function __construct($studentEmail, $testID, $status, $firstResponseDate, $lastResponseDate) {
        $this->studentEmail = $studentEmail;
        $this->testID = $testID;
        $this->status = $status;
        $this->firstResponseDate = $firstResponseDate;
        $this->lastResponseDate = $lastResponseDate;
    }

    public static function getTests($idTest, $stdEmail)
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

    public static function start($testId, $stdEmail) {
        global $con; // Assumi che $con sia la tua connessione al database

        // Prepara la query per controllare l'esistenza del record
        $checkQuery = "SELECT * FROM Svolgimento WHERE MailStudente = ? AND IDTest = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param('si', $stdEmail, $testId);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentTime = date('Y-m-d H:i:s'); // Ottieni il timestamp corrente

        if ($result->num_rows > 0) {
            // Il record esiste, aggiorna DataUltimaRisposta
            $updateQuery = "UPDATE Svolgimento SET DataUltimaRisposta = ?, Stato = ? WHERE MailStudente = ? AND IDTest = ?";
            $updateStmt = $con->prepare($updateQuery);
            $status = self::IN_PROGRESS;
            $updateStmt->bind_param('sssi', $currentTime,$status, $stdEmail, $testId);
            $updateStmt->execute();
        } else {
            // Il record non esiste, inseriscilo
            $insertQuery = "INSERT INTO Svolgimento (MailStudente, Stato, DataPrimaRisposta, DataUltimaRisposta, IDTest) VALUES (?, 'Open', ?, ?, ?)";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param('sssi', $stdEmail, $currentTime, $currentTime, $testId);
            $insertStmt->execute();
        }
    }

    public function getStudentEmail() {
        return $this->studentEmail;
    }

    public function setStudentEmail($studentEmail) {
        $this->studentEmail = $studentEmail;
    }

    public function getTestID() {
        return $this->testID;
    }

    public function setTestID($testID) {
        $this->testID = $testID;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        switch ($status) {
            case self::OPEN:
            case self::IN_PROGRESS:
            case self::CLOSE:
                $this->status = $status;
                break;
            default:
                throw new InvalidArgumentException("Invalid status");
        }
    }

    public function getFirstResponseDate() {
        return $this->firstResponseDate;
    }

    public function setFirstResponseDate($firstResponseDate) {
        $this->firstResponseDate = $firstResponseDate;
    }

    public function getLastResponseDate() {
        return $this->lastResponseDate;
    }

    public function setLastResponseDate($lastResponseDate) {
        $this->lastResponseDate = $lastResponseDate;
    }
}


?>