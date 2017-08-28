#!/bin/bash
TAR_BZ2_DIR="/home"
USERNAME="dgilbert"
TAR_BZ2_NAME="$USERNAME.tar.bz2"
USR_HOME_DIR="/home/$USERNAME"
PRJ_OID_STR="520bf81a807b3632050c5a7c"
USR_OID_STR="520aa241807b366e05849361"
CONF_REM_USR_URL="http://localhost/ssh_mgr/dev/ConfRemUser.php"
sudo tar cjfP $TAR_BZ2_DIR/$TAR_BZ2_NAME $USR_HOME_DIR
sudo userdel -r $USERNAME
# email project manager with remove user notification
# email project user with remove user notification and his or her attached data
curl --data "confRemUser=1&projOIDstr=$PRJ_OID_STR&userOIDstr=$USR_OID_STR" $CONF_REM_USR_URL