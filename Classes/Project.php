<?php
require_once('SSHMgrMongo.php');
require_once('TokenGenerator.php');
require_once('User.php');
class Project {
    private $_id;
    private $name;
    private $ipV4Addr;
    private $users = array();
    private $userCount;
    public function __construct($_id = NULL) {
        $this->_id = $_id;
    }
    // Accessor Methods
    public function get_id() {
        return $this->_id;
    }
    // Precondition: $this->id is set and exists in MongoDB ssh_mgr/Projects
    // collection
    public function getName() {
        if (empty($this->name)) {
            $mongoProjDoc = SSHMgrMongo::getProjC()->findOne(
                array(
                    '_id' => $this->_id
                ),
                array('name')
            );
            $this->name = $mongoProjDoc['name'];
        }
        return $this->name;
    }
    // Precondition: $this->id is set and exists in MongoDB ssh_mgr/Projects
    // collection
    public function getIPv4Addr() {
        if (empty($this->ipV4Addr)) {
            $mongoProjDoc = SSHMgrMongo::getProjC()->findOne(
                array(
                    '_id' => $this->_id
                ),
                array('ipV4Addr')
            );
            $this->ipV4Addr = $mongoProjDoc['ipV4Addr'];
        }
        return $this->ipV4Addr;
    }
    private function exists() {
        if (SSHMgrMongo::getProjC()->findOne(array(
            'name' => $this->name
        )) == null) {
            return false;
        } else {
            return true;
        }
    }
    private function hasUser($user) {
        if (SSHMgrMongo::getProjUserConC()->findOne(array(
            'projID' => $this->_id,
            'userID' => $user->get_id()
        ))) {
            return true;
        } else {
            return false;
        }
    }
    // re-write to cache in $this->userCount
    public function getUserCount() {
        $userCount = SSHMgrMongo::getProjUserConC()->find(array(
            'projID' => $this->_id
        ))->count();
        if ($userCount > 0) {
            return $userCount;
        } else {
            return 0;
        }
    }
    // Preconditions: * $this->_id is set
    //                * Project has users (can determine with $this->getUserCount())
    public function getUsers() {
        $mongoUserDocs = SSHMgrMongo::getProjUserConC()->find(
                array(
                    'projID' => $this->_id
                ),
                array(
                    'userID',
                    'status'
                )
        );
        foreach ($mongoUserDocs as $mongoUserDoc) {
            $user = new User();
            $user->set_id($mongoUserDoc['userID']);
            $user->setStatus($mongoUserDoc['status']);
            $user->setFirstNameFromDB();
            $user->setLastNameFromDB();
            $this->users[] = $user;
        }
        $this->sortUsers();
        return $this->users;
    }
    // Mutator Methods
    public function set_id($_id) {
        $this->_id = $_id;
    }
    public function set_idFromDB() {
        $mongoUserDoc = SSHMgrMongo::getProjC()->findOne(
                array(
                    'name' => $this->name
                ),
                array('_id')
        );
        $this->_id = $mongoUserDoc['_id'];
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function setIPv4Addr($ipV4Addr) {
        $this->ipV4Addr = $ipV4Addr;
    }
    public function add() {
        if ($this->exists()) {
            die ('This project already exists.
                  Please communicate this to the systems administrator.');
        } else {
            SSHMgrMongo::getProjC()->insert(array(
                'name' => $this->name,
                'ipV4Addr' => $this->ipV4Addr
            ));
        }
    }
    public function remove() {
        if ($this->exists()) {
            SSHMgrMongo::getProjC()->remove(array(
                'name' => $this->name
            ));
            SSHMgrMongo::getProjUserConC()->remove(array(
                'projID' => $this->_id
            ));
        } else {
            die ('This project does not exist.
                  Please communicate this to the systems administrator.');
        }
    }
    public function pendingAddUser($user) {
        if ($this->hasUser($user)) {
            die ('This project already has this user.
                  Please communicate this to the systems administrator.');
        } else {
            SSHMgrMongo::getProjUserConC()->insert(array(
                'projID' => $this->_id,
                'userID' => $user->get_id(),
                'status' => 'Pending Add',
                'defaultPW' => TokenGenerator::GenerateToken(),
                'addToken' => TokenGenerator::GenerateToken()
            ));
        }
    }
    private function sortUsers() {
        usort($this->users, array("User", "CmpUser"));
    }
    public function cancelPendingAddUser($user) {
        SSHMgrMongo::getProjUserConC()->remove(array(
            'projID' => $this->_id,
            'userID' => $user->get_id()
        ));
    }
    // write modular code that re-uses array(projID,userID)
    // and code that returns an id for that data. just use id
    // in all other functions
    public function confAddUser($user) {
        SSHMgrMongo::getProjUserConC()->update(
                array(
                    'projID' => $this->_id,
                    'userID' => $user->get_id()
                ),
                array(
                    '$set' => array('status' => 'Added')
                )
        );
    }
    // Static Methods
    // Create a separate function for sorting an array of projects,
    // rather than sort with MongoCursor
    public static function GetProjects($field, $order) {
        $mongoProjDocs =  SSHMgrMongo::getProjC()->find()->sort(array($field => $order));
        foreach ($mongoProjDocs as $mongoProjDoc) {
            $projects[] = new Project($mongoProjDoc['_id']);
        }
        return $projects;
    }
}
?>
