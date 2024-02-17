<?php

class MessageList {
    private $messages;

    public function __construct() {
        $this->messages = [];
    }

    public function addMessage(Message $message) {
        $this->messages[] = $message;
    }

    public function getMessages() {
        return $this->messages;
    }
}


?>
