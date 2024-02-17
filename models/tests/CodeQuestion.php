<?php

class CodeQuestion extends Question {
    private $output;

    public function __construct($IDTest, $ID, $output) {
        parent::__construct($ID, $IDTest);
        $this->output = $output;
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