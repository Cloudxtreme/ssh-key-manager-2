#!/bin/bash
# Precondition: system running this script must have curl installed and
# accessible from this script
USERNAME="dgilbert"
USR_PASSWORD="abc"
USR_HOME_DIR="/home/$USERNAME"
SSH_DIR="$USR_HOME_DIR/.ssh"
USR_PUB_KEY_VAL="keyVal"
USR_FIRST_NAME="Daniel"
USR_LAST_NAME="Gilbert"
USR_EMAIL="daniel.gilbert@omniplatform.com"
PRJ_NAME="Project1"
PRJ_BRNCH_DIR="$USR_HOME_DIR/$PRJ_NAME"
PRJ_TRUNK_DIR=""
PRJ_OID_STR="520bf81a807b3632050c5a7c"
USR_OID_STR="520aa241807b366e05849361"
CONF_ADD_USR_URL="http://localhost/ssh_mgr/dev/ConfAddUser.php"
sudo useradd -s /bin/bash -m $USERNAME
echo "$USERNAME:$USR_PASSWORD" | sudo chpasswd
sudo mkdir -m 700 $SSH_DIR
echo "$USR_PUB_KEY_VAL" | sudo tee -a $SSH_DIR/authorized_keys > /dev/null
sudo chmod 600 $SSH_DIR/authorized_keys
sudo chown -R $USERNAME:$USERNAME $USR_HOME_DIR
sudo su dgilbert -c "bzr whoami \"$USR_FIRST_NAME $USR_LAST_NAME <$USR_EMAIL>\""
sudo su dgilbert -c "bzr init-repo $PRJ_BRNCH_DIR" > /dev/null
# need trunk directories for projects (Get with 'Add Project')
# sudo su dgilbert -c "bzr branch $PRJ_TRUNK_DIR $PRJ_BRNCH_DIR"
# confirm add user
curl --data "confAddUser=1&projOIDstr=$PRJ_OID_STR&userOIDstr=$USR_OID_STR" $CONF_ADD_USR_URL
# email project manager and user to notify of user add