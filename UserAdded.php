<?php session_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <title>User Added</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <h3>Thank you.</h3>
        Your public key has been uploaded.
        <br>
        Here is your reference number as a receipt:
        <?php echo $_SESSION['userOIDstr'] ?>
    </body>
</html>
