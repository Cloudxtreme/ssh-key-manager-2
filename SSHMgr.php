<?php
#date_default_timezone_set('America/Los_Angeles');
define('CLASS_PATH', 'Classes/');
// Import Classes
require_once(CLASS_PATH . 'HTMLForm.php');
require_once(CLASS_PATH . 'Project.php');
require_once(CLASS_PATH . 'User.php');
// Pending Add User Vars
$pAddUserForm = new HTMLForm('SSHMgr.php', 'POST', 'pAddUser');
$pAddUserForm->addField('projOIDstr', 'Project ID', 'alphaNum');
$pAddUserForm->addField('userOIDstr', 'User ID', 'alphaNum');
$pAddUserForm->addField('pAddUser', 'Pending Add User', 'alphaSpace', 'Add');
// Cancel Pending Add User Vars
$canUserForm = new HTMLForm('SSHMgr.php', 'POST', 'canUser');
$canUserForm->addField('projOIDstr', 'Project ID', 'alphaNum');
$canUserForm->addField('userOIDstr', 'User ID', 'alphaNum');
$canUserForm->addField('canUser', 'Cancel Pending Add User', 'alphaSpace', 'Cancel');
// Add Project Vars
$addProjForm = new HTMLForm('SSHMgr.php', 'POST', 'addProject');
$addProjForm->addField('projName', 'Project Name', 'alphaNumSpace');
$addProjForm->addField('projIPv4Addr', 'Project IPv4 Address', 'ipV4Addr');
$addProjForm->addField('addProject', 'Add Project', 'alphaSpace', 'Add');
// Remove Project Vars
$remProjForm = new HTMLForm('SSHMgr.php', 'POST', 'remProject');
$remProjForm->addField('projName', 'Project Name', 'alphaNumSpace');
$remProjForm->addField('remProject', 'Remove Project', 'alphaSpace', 'Remove');
// Pending Add User Logic
if ($pAddUserForm->isSubmitted()) {
    if ($pAddUserForm->isValid()) {
        $project = new Project();
        $project->set_id(new MongoId($pAddUserForm->getField('projOIDstr')->getValue()));
        $user = new User();
        $user->set_id(new MongoId($pAddUserForm->getField('userOIDstr')->getValue()));
        $project->pendingAddUser($user);
    }
}
// Cancel Pending Add User Logic
if ($canUserForm->isSubmitted()) {
    if ($canUserForm->isValid()) {
        $project = new Project();
        $project->set_id(new MongoId($canUserForm->getField('projOIDstr')->getValue()));
        $user = new User();
        $user->set_id(new MongoId($canUserForm->getField('userOIDstr')->getValue()));
        $project->cancelPendingAddUser($user);
    }
}
// Add Project Logic
if ($addProjForm->isSubmitted()) {
    if ($addProjForm->isValid()) {
        $project = new Project();
        $project->setName($addProjForm->getField('projName')->getValue());
        $project->setIPv4Addr($addProjForm->getField('projIPv4Addr')->getValue());
        $project->add();
        $addProjForm->clearFieldValues();
    }
}
// Remove Project Logic
if ($remProjForm->isSubmitted()) {
    if ($remProjForm->isValid()) {
        $project = new Project();
        $project->setName($remProjForm->getField('projName')->getValue());
        $project->set_idFromDB();
        $project->remove();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SSH Manager</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <h1>SSH Manager</h1>
        <!-- Add/Remove Users -->
        <h2>Add/Remove Users</h2>
        <?php
            $projects = Project::GetProjects('name', 1);
            if (count($projects) === 0) {
                echo 'No current projects.';
            } else {
                echo <<<EOF
                <table border="1">
                <tr>
                <td><b><u>Project Name</u></b></td>
                <td><b><u>User Name</u></b></td>
                <td><b><u>User Status</u></b></td>
                <td><b><u>Add/Remove User</u></b></td>
                </tr>
EOF;
                foreach ($projects as $project) {
                    $projUserCount = $project->getUserCount();
                    if ($projUserCount === 0) {
                        echo <<<EOF
                        <!-- 1st row with project name, and indicator of no users -->
                        <tr>
                            <td>
                                {$project->getName()}
                            </td>
                            <td>
                                (None)
                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                        </tr>
EOF;
                    } else if ($projUserCount > 0) {
                        $users = $project->getUsers();
                        $firstUser = $users[0];
                        echo <<<EOF
                        <form
                            action="{$canUserForm->getAction()}"
                            method="{$canUserForm->getMethod()}">
                            <!-- 1st row with project name, and first user -->
                            <tr>
                                <td rowspan="{$projUserCount}" valign="top">
                                    {$project->getName()}
                                </td>
                                <td>
                                    {$firstUser->getFirstName()} {$firstUser->getLastName()}
                                    <input
                                        type="hidden"
                                        name="{$canUserForm->getField('projOIDstr')->getName()}"
                                        value="{$project->get_id()}">
                                    <input
                                        type="hidden"
                                        name="{$canUserForm->getField('userOIDstr')->getName()}"
                                        value="{$firstUser->get_id()}">
                                </td>
                                <td>
                                    {$firstUser->getStatus()}
                                </td>
                                <td>
                                    <input
                                        type="submit"
                                        name="{$canUserForm->getField('canUser')->getName()}"
                                        value="{$canUserForm->getField('canUser')->getValue()}">
                                </td>
                            </tr>
                        </form>
EOF;
                        if ($projUserCount > 1) {
                            for ($i = 1; $i < $projUserCount; $i++) {
                                $user = $users[$i];
                                echo <<<EOF
                                <form
                                    action="{$canUserForm->getAction()}"
                                    method="{$canUserForm->getMethod()}">
                                    <!-- Remaining rows of users -->
                                    <tr>
                                        <td>
                                            {$user->getFirstName()} {$user->getLastName()}
                                            <input
                                                type="hidden"
                                                name="{$canUserForm->getField('projOIDstr')->getName()}"
                                                value="{$project->get_id()}">
                                            <input
                                                type="hidden"
                                                name="{$canUserForm->getField('userOIDstr')->getName()}"
                                                value="{$user->get_id()}">
                                        </td>
                                        <td>
                                            {$user->getStatus()}
                                        </td>
                                        <td>
                                            <input
                                                type="submit"
                                                name="{$canUserForm->getField('canUser')->getName()}"
                                                value="{$canUserForm->getField('canUser')->getValue()}">
                                        </td>
                                    </tr>
                                </form>
EOF;
                            }
                        }
                    }
                    echo <<<EOF
                    <!-- Add user to project -->
                    <form
                        action="{$pAddUserForm->getAction()}"
                        method="{$pAddUserForm->getMethod()}">
                        <tr>
                            <td>
                                <input
                                    type="hidden"
                                    name="{$pAddUserForm->getField('projOIDstr')->getName()}"
                                    value="{$project->get_id()}">
                            </td>
                            <td>
                                <select name="{$pAddUserForm->getField('userOIDstr')->getName()}">
EOF;
                                    echo '<option value="">Select User</option>';
                                    foreach (User::GetUsers('firstName', 1) as $mongoUserDoc) {
                                        echo "<option value=\"{$mongoUserDoc['_id']}\">{$mongoUserDoc['firstName']} {$mongoUserDoc['lastName']}</option>";
                                    }
                    echo <<<EOF
                                </select>
                            </td>
                            <td>

                            </td>
                            <td>
                                <input
                                    type="submit"
                                    name="{$pAddUserForm->getField('pAddUser')->getName()}"
                                    value="{$pAddUserForm->getField('pAddUser')->getValue()}">
                            </td>
                        </tr>
                    </form>
EOF;
                }
                echo '</table>';
            }
        ?>
        
        <!-- Add/Remove Projects -->
        <h2>Add/Remove Projects</h2>
        <table>
            <tr>
                <td><b><u>Project Name</u></b></td>
                <td><b><u>Project IPv4 Address</u></b></td>
                <td><b><u>Add/Remove Project</u></b></td>
            </tr>
            <!-- Remove Projects -->
            <?php
                foreach ($projects as $project) {
                    echo <<<EOF
                    <form
                        action="{$remProjForm->getAction()}"
                        method="{$remProjForm->getMethod()}">
                        <tr>
                            <!-- Remove Project Name -->
                            <td>
                                {$project->getName()}
                                <input
                                    type="hidden"
                                    name="{$remProjForm->getField('projName')->getName()}"
                                    value="{$project->getName()}">
                            </td>
                            <!-- Remove Project IPv4 Address -->
                            <td>
                                {$project->getIPv4Addr()}
                            </td>
                            <!-- Remove -->
                            <td>
                                <input
                                    type="submit"
                                    name="{$remProjForm->getField('remProject')->getName()}"
                                    value="{$remProjForm->getField('remProject')->getValue()}">
                            </td>
                        </tr>
                    </form>
EOF;
                }
            ?>
            <!-- Add Projects -->
            <form
                action="<?php echo $addProjForm->getAction() ?>"
                method="<?php echo $addProjForm->getMethod() ?>">
                <tr>
                    <td>
                        <!-- Add Project Name -->
                        <?php
                            foreach ($addProjForm->getField('projName')->getErrorMessages() as $errorMessage) {
                                echo $errorMessage->getMessage() . '<br>';
                            }
                        ?>
                        <input
                            type="text"
                            name="<?php echo $addProjForm->getField('projName')->getName() ?>"
                            value="<?php echo $addProjForm->getField('projName')->getValue() ?>"
                            maxlength="<?php  ?>">
                    </td>
                    <td>
                        <!-- Add Project IPv4 Address -->
                        <?php
                            foreach ($addProjForm->getField('projIPv4Addr')->getErrorMessages() as $errorMessage) {
                                echo $errorMessage->getMessage() . '<br>';
                            }
                        ?>
                        <input
                            type="text"
                            name="<?php echo $addProjForm->getField('projIPv4Addr')->getName() ?>"
                            value="<?php echo $addProjForm->getField('projIPv4Addr')->getValue() ?>"
                            maxlength="<?php  ?>">
                    </td>
                    <td>
                        <!-- Add -->
                        <input
                            type="submit"
                            name="<?php echo $addProjForm->getField('addProject')->getName() ?>"
                            value="<?php echo $addProjForm->getField('addProject')->getValue() ?>">
                    </td>
                </tr>
            </form>
        </table>
    </body>
</html>
