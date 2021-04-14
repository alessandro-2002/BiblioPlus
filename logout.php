<!DOCTYPE html>
<html lang="it">

<head>
    <title>Logout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <?php

    require_once('assets/user_check_login.php');

    try {
        $account->logout();
        echo '<br><div class="alert alert-success">
                <strong>Logout effettuato!</strong> Logout effettuato con successo, verrai reindirizzato alla home.
            </div>';

        header('Refresh: 2; URL=/');
        die();
    } catch (Exception $e) {

    ?>
        <div class="alert alert-danger">
            <strong>Errore!</strong> Errore durante il logout, verrai reindirizzato alla home.

        </div>

    <?php
        header('Refresh: 3; URL=/');
    }

    ?>
</body>

</html>