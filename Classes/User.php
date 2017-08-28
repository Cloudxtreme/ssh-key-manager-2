<?php
require_once('SSHMgrMongo.php');
class User {
    private $_id;
    private $firstName;
    private $lastName;
    private $pubKey;
    private $status; // Relative to business logic
    // Accessor Methods
    public function get_id() {
        return $this->_id;
    }
    public function getFirstName() {
        return $this->firstName;
    }
    public function getLastName() {
        return $this->lastName;
    }
    public function getStatus($project = '') {
        if (empty($this->status) && $project !== '') {
            $mongoPUCDoc = SSHMgrMongo::getProjUserConC()->findOne(
                array(
                    'projID' => $project->get_id(),
                    'userID' => $this->_id
                ),
                array('status')
            );
            $this->status = $mongoPUCDoc['status'];
        }
        return $this->status;
    }
    public function exists() {
        if (SSHMgrMongo::getUserC()->findOne(array(
            'firstName' => $this->firstName,
            'lastName' => $this->lastName
        )) == null) {
            return false;
        } else {
            return true;
        }
    }
    // Mutator Methods
    public function set_id($_id) {
        $this->_id = $_id;
    }
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }
    // Precondition: $this->_id exists in DB
    public function setFirstNameFromDB() {
        $mongoUserDoc = SSHMgrMongo::getUserC()->findOne(
                array(
                    '_id' => $this->_id
                ),
                array('firstName')
        );
        $this->firstName = $mongoUserDoc['firstName'];
    }
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    // Precondition: $this->_id exists in DB
    public function setLastNameFromDB() {
        $mongoUserDoc = SSHMgrMongo::getUserC()->findOne(
                array(
                    '_id' => $this->_id
                ),
                array('lastName')
        );
        $this->lastName = $mongoUserDoc['lastName'];
    }
    public function setPubKey($pubKey) {
        $this->pubKey = file_get_contents($pubKey['tmp_name']);
        if ($this->pubKey === false) {
            die ('Could not read contents of public key.
                  Please communicate this to the systems administrator.');
        }
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function add() {
        if ($this->exists()) {
            die ('This user already exists.
                  Please communicate this to the systems administrator.');
        } else {
            $_id = new MongoId();
            while (SSHMgrMongo::getUserC()->findOne(array(
                '_id' => $_id
            )) != null) {
                $_id = new MongoId();
            }
            $this->_id = $_id;
            SSHMgrMongo::getUserC()->insert(array(
                '_id' => $this->_id,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'pubKey' => $this->pubKey
            ));
        }
    }
    // Static Methods
    public static function GetUsers($field, $order) {
        return SSHMgrMongo::getUserC()->find()->sort(array($field => $order));
    }
    public static function CmpUser($a, $b) {
        $al = strtolower($a->firstName);
        $bl = strtolower($b->firstName);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }
}
?>
