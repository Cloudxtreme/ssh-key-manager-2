<?php
require_once('HTMLField.php');
class HTMLForm {
    private $action;
    private $method;
    private $submitButtonName;
    private $encType;
    private $fields = array();
    private $isValid;
    
    public function __construct($action, $method, $submitButtonName, $encType = '') {
        $this->action = $action;
        $this->method = $method;
        $this->submitButtonName = $submitButtonName;
        $this->encType = $encType;
    }
    public function getAction() {
        return $this->action;
    }
    public function getMethod() {
        return $this->method;
    }
    public function getEncType() {
        return $this->encType;
    }
    public function addField($name, $title, $type, $value = '') {
        $this->fields[$name] = new HTMLField($name, $title, $type, $value);
    }
    public function getField($name) {
        return $this->fields[$name];
    }
    private function populateFieldValues() {
        foreach ($this->fields as $field) {
            switch ($this->method) {
                case 'POST':
                    if (isset($_POST[$field->getName()])) {
                        $field->setValue($_POST[$field->getName()]);
                    }
                    break;
                case 'GET':
                    if (isset($_GET[$field->getName()])) {
                        $field->setValue($_GET[$field->getName()]);
                    }
                    break;
            }
            if ($field->getType() == 'file') {
                $field->setValue($_FILES[$field->getName()]);
            }
        }
    }
    public function clearFieldValues() {
        foreach ($this->fields as $field) {
            if ($field->getName() != $this->submitButtonName) {
                $field->setValue(null);
            }
        }
    }
    public function isValid() {
        $this->populateFieldValues();
        $this->isValid = true;
        foreach ($this->fields as $field) {
            if ($field->notValid()) {
                $this->isValid = false;
            }
        }
        return $this->isValid;
    }
    public function isSubmitted() {
        switch ($this->method) {
            case 'POST':
                if (!empty($_POST[$this->submitButtonName])) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'GET':
                if (!empty($_GET[$this->submitButtonName])) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
}
?>
