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