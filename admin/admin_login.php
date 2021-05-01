<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Login</title>
</head>

<body>
    <?php

    require_once("../assets/db.php");
    require_once('../classes/admin_class.php');
    require_once('../assets/admin_session_login.php');

    try {
        //se è già connesso lo reindirizzo in home 
        if ($login) {
            header('Location: index.php');

            //altrimenti controllo se sta facendo login in post
        } else if (isset($_POST['mail']) && isset($_POST['password'])) {
            $login = $adminAccount->login($_POST['mail'], $_POST['password']);

            if ($login) {
                echo '<br><div class="alert alert-success">
                        <strong>Login effettuato!</strong> Login effettuato con successo, verrai reindirizzato alla home.
                        </div>';

                header('Refresh: 2; URL=index.php');
                die();
            } else {
                echo '<br><div class="alert alert-danger fade in">
                        <strong>Login fallito!</strong> Nome utente e/o password errati.
                    </div>';
            }
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">
                <strong>Errore!</strong> Errore durante il login.
            </div>';
    }
    ?>

    <!-- form di login -->
    <center>
        <div id="login">
            <header>
                <p>Login Bibliotecario</p>
            </header>
            <form id="form" action="admin_login.php" method="post">
                <input id="txt" type="email" placeholder="E-Mail" name="mail" required><br>
                <input id="txt" type="password" placeholder="Password" name="password" required><br>
                <hr>
                <input type="submit" id="button" value="Login">
            </form>
        </div>
        <br>

    </center>
</body>

</html>