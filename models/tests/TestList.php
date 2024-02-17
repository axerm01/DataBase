<?php

class TestList {
    private $tests = [];

    public function addTest(Test $test) {
        foreach ($this->tests as $existingTest) {
            if ($existingTest->getId() === $test->getId()) {
                // Già esiste un test con questo ID
                return false;
            }
        }
        $this->tests[$test->getId()] = $test;
        return true;
    }

    public function removeTest($id) {
        foreach ($this->tests as $key => $test) {
            if ($test->getId() === $id) {
                unset($this->tests[$key]);
                return true;
            }
        }
        return false;
    }

    public function getTestById($id) {
        foreach ($this->tests as $test) {
            if ($test->getId() === $id) {
                return $test;
            }
        }
        return null; // Nessun test trovato con questo ID
    }
}

?>


}