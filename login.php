<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">

    <title>Login</title>
</head>

<body>
    <div class="login text-center">
        <form action="" method="POST">

            <?php

            require_once("assets/db.php");
            require_once('classes/user_class.php');
            require_once('assets/session_login.php');

            try {
                //se è già connesso lo reindirizzo in home 
                if ($login) {
                    header('Location: /');

                    //altrimenti controllo se sta facendo login in post
                } else if (isset($_POST['mail']) && isset($_POST['password'])) {
                    $login = $account->login($_POST['mail'], $_POST['password']);

                    if ($login) {
                        echo '<br><div class="alert alert-success">
                        <strong>Login effettuato!</strong> Login effettuato con successo, verrai reindirizzato alla home.
                        </div>';

                        header('Refresh: 2; URL=/');
                        die();
                    } else {
                        echo '<br><div class="alert alert-danger">
                        <strong>Login fallito!</strong> Nome utente e/o password errati.
                    </div><br>';
                    }
                }
            } catch (Exception $e) {
                echo '<br><div class="alert alert-danger">
                <strong>Errore!</strong> Errore durante il login.
            </div><br>';
            }
            ?>

            <a href="index.php"><img class="mb-4" src="img/logo.svg" alt="" width="150"></a>
            <br>

            <h1 class="h3 mb-3 fw-normal">Login Utente</h1>

            <div class="form-floating">
                <input name="mail" type="email" class="form-control firstElement" id="floatingInput" placeholder="E-Mail" required>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control lastElement" id="floatingInput" placeholder="Password" required>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            <a href="register.php" class="w-100 btn btn-lg btn-link" role="button">Crea un account</a>
        </form>
    </div>
</body>

</html>