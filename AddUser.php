<?php
session_start();
define('CLASS_PATH', 'Classes/');
// Import Classes
require_once(CLASS_PATH . 'AddUserConsts.php');
require_once(CLASS_PATH . 'HTMLForm.php');
require_once(CLASS_PATH . 'User.php');
// Add User Vars
$addUserForm = new HTMLForm('AddUser.php', 'POST', 'addUser', 'multipart/form-data');
$addUserForm->addField('firstName', 'First Name', 'alphaSpaces');
$addUserForm->addField('lastName', 'Last Name', 'alphaSpaces');
$addUserForm->addField('pubKey', 'Public Key', 'file');
$addUserForm->addField('addUser', 'Add User', 'alphaSpaces', AddUserConsts::HTML_TITLE_ADD_USER);
// Add User Logic
if ($addUserForm->isSubmitted()) {
    if ($addUserForm->isValid()) {
        $user = new User();
        $user->setFirstName($addUserForm->getField('firstName')->getValue());
        $user->setLastName($addUserForm->getField('lastName')->getValue());
        $user->setPubKey($addUserForm->getField('pubKey')->getValue());
        $user->add();
        $_SESSION['userOIDstr'] = (string) $user->get_id();
        $addUserForm->clearFieldValues();
        header('Location:UserAdded.php');
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo AddUserConsts::HTML_TITLE_ADD_USER ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <h1><?php echo AddUserConsts::HTML_TITLE_ADD_USER ?></h1>
        <form
            action="<?php echo $addUserForm->getAction() ?>"
            method="<?php echo $addUserForm->getMethod() ?>"
            enctype="<?php echo $addUserForm->getEncType() ?>">
            <!-- Input First Name -->
            <?php
                foreach ($addUserForm->getField('firstName')->getErrorMessages() as $errorMessage) {
                    echo $errorMessage->getMessage() . '<br>';
                }
            ?>
            <?php echo $addUserForm->getField('firstName')->getTitle() ?>:
            <input
                type="text"
                name="<?php echo $addUserForm->getField('firstName')->getName() ?>"
                value="<?php echo $addUserForm->getField('firstName')->getValue() ?>"
                maxlength="<?php  ?>">
            <br>
            <!-- Input Last Name -->
            <?php
                foreach ($addUserForm->getField('lastName')->getErrorMessages() as $errorMessage) {
                    echo $errorMessage->getMessage() . '<br>';
                }
            ?>
            <?php echo $addUserForm->getField('lastName')->getTitle() ?>:
            <input
                type="text"
                name="<?php echo $addUserForm->getField('lastName')->getName() ?>"
                value="<?php echo $addUserForm->getField('lastName')->getValue() ?>"
                maxlength="<?php  ?>">
            <br>
            <!-- Select Public Key -->
            <?php
                foreach ($addUserForm->getField('pubKey')->getErrorMessages() as $errorMessage) {
                    echo $errorMessage->getMessage() . '<br>';
                }
            ?>
            <?php echo $addUserForm->getField('pubKey')->getTitle() ?>:
            <input
                type="file"
                name="<?php echo $addUserForm->getField('pubKey')->getName() ?>">
            <br>
            <!-- <?php echo AddUserConsts::HTML_TITLE_ADD_USER ?> -->
            <input
                type="submit"
                name="<?php echo $addUserForm->getField('addUser')->getName() ?>"
                value="<?php echo $addUserForm->getField('addUser')->getValue() ?>">
        </form>
    </body>
</html>
