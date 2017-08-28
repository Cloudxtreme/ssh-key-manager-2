<?php
require_once('Validation.php');
require_once('ErrorMessage.php');
class HTMLField {
    private $name;
    private $title;
    private $type;
    private $value;
    private $notValid;
    private $errorMessages = array();
    public function __construct($name, $title, $type, $value = '') {
        $this->name = $name;
        $this->title = $title;
        $this->type = $type;
        $this->value = $value;
    }
    public function getName() {
        return $this->name;
    }
    public function getTitle() {
        return $this->title;
    }
    public function getType() {
        return $this->type;
    }
    public function setValue($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
    public function notValid() {
        $this->notValid = false;
        switch ($this->type) {
            case 'alphaSpace':
                if (Validation::notAlphaSpace($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::NOT_ALPHA_SPACE_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
            case 'file':
                if (Validation::fileUploadError($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::FILE_UPLOAD_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
            case 'alphaNumSpace':
                if (Validation::notAlphaNumSpace($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::NOT_ALPHANUM_SPACE_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
            case 'ipV4Addr':
                if (Validation::notIPv4Addr($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::NOT_IPV4_ADDR_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
            case 'alphaNum':
                if (Validation::notAlphaNumeric($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::NOT_ALPHANUM_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
            case 'one':
                if (Validation::notOne($this->value)) {
                    $this->notValid = true;
                    $errorMessage = $this->title . Validation::NOT_ONE_ERROR_MESSAGE;
                    $this->errorMessages[] = new ErrorMessage($errorMessage);
                }
                break;
        }
        return $this->notValid;
    }
    public function getErrorMessages() {
        return $this->errorMessages;
    }
}
?>
