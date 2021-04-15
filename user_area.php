<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/user_area.css">
    <title>Biblioteca facile!</title>
</head>

<body>

    <div class="content">
        <?php
        require_once('assets/header.php');

        //controllo che ci sia autenticazione o faccio redirect
        if (!$account->isAuthenticated()) {
            header('Location: /');
            die();
        }

        ?>

        <a href="change_password.php">Cambio password</a>



    </div>

</body>

</html>