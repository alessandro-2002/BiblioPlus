<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Registrazione</title>
</head>

<body>
    <?php

    require_once("assets/db.php");
    require_once('classes/user_class.php');
    require_once('assets/session_login.php');

    try {
        //se è già connesso lo reindirizzo in home 
        if ($login) {
            header('Location: /');

            //altrimenti controllo se sta facendo registrazione in post
        } else if (isset($_POST['mail']) && isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['name']) && isset($_POST['surname'])) {

            //controllo che le 2 password corrispondano
            if ($_POST['password1'] != $_POST['password2']) {
                throw new Exception("Le 2 password non corrispondono.");
            }

            //controllo la validità del file di avatar se inserito
            if ($_FILES['avatar']['size'] > 0) {
                // Controllo che il file non superi i 3 MB
                if ($_FILES['avatar']['size'] > 3145728) {
                    throw new Exception("L'avatar non deve superare i 3 MB");
                }

                // Ottengo le informazioni sull'immagine
                list($width, $height, $type, $attr) = getimagesize($_FILES['avatar']['tmp_name']);

                // Controllo che il file sia in uno dei formati GIF, JPG o PNG
                if (($type != 1) && ($type != 2) && ($type != 3)) {
                    throw new Exception("L'avatar deve essere un'immagine GIF, JPG o PNG.");
                }
            }

            //controllo i valori opzionali se esistono per passarli NULL in caso non siano inseriti
            if (!isset($_POST['address']) || $_POST['address'] == "") {
                $address = NULL;
            } else {
                $address = $_POST['address'];
            }

            //procedo alla registrazione
            $idRegistrato = $account->addAccount($_POST['mail'], $_POST['password1'], $_POST['name'], $_POST['surname'], $address);

            //aggiungo avatar se inserito
            if ($_FILES['avatar']['size'] > 0) {
                $account->editAvatar($idRegistrato, $_FILES['avatar']);
            }

            echo '<br><div class="alert alert-success">
                    <strong>Registrazione effettuata!</strong> Utente registrato con successo con id ' . $idRegistrato . ', verrai reindirizzato al login.
                </div>';

            header('Refresh: 5; URL=login.php');
            die();
        }
    } catch (Exception $e) {
        echo '<br><div class="alert alert-danger">
                <strong>Errore!</strong> ' . $e->getMessage() .
            '</div>';
    }
    ?>

    <!-- form di registrazione -->
    <center>
        <div id="login">
            <header>
                <p>Registrazione Utente</p>
            </header>
            <form id="form" action="register.php" method="post" enctype="multipart/form-data">
                <input id="txt" type="text" maxlength="255" placeholder="E-Mail" name="mail" required><br>
                <input id="txt" type="password" minlength="6" maxlength="15" placeholder="Password" name="password1" required><br>
                <input id="txt" type="password" minlength="6" maxlength="15" placeholder="Ripeti Password" name="password2" required><br>
                <input id="txt" type="text" maxlength="45" placeholder="Nome" name="name" required><br>
                <input id="txt" type="text" maxlength="45" placeholder="Cognome" name="surname" required><br>
                <input id="txt" type="text" maxlength="100" placeholder="Indirizzo" name="address"><br>

                Avatar
                <input id="txt" type="file" name="avatar"><br>
                <hr>
                <input type="submit" id="button" value="Register">
            </form>
        </div>

        <!-- Link in caso sia già registrato che rimanda a login -->
        Sei gi&agrave; registrato? <a href="login.php">Accedi</a>
        <br>

    </center>
</body>

</html>