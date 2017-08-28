<?php
class ErrorMessage {
    private $message;
    public function __construct($message) {
        $this->message = $message;
    }
    public function getMessage() {
        return $this->message;
    }
}
?>
