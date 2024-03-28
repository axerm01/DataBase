<?php

class Professor {

    public function sendMesssage(Message $message) {
        $this->sentMessages->addMessage($message);
    }

    public function receiveMessage(Message $message) {
        $this->receivedMessages->addMessage($message);
    }

    public function getSentMessages() {
        return $this->sentMessages->getMessages();
    }

    public function getReceivedMessages() {
        return $this->receivedMessages->getMessages();
    }
}