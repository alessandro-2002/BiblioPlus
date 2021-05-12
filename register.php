<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">

    <title>Registrazione</title>
</head>

<body>
    <div class="login text-center">
        <form action="" method="POST" enctype="multipart/form-data">

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

                    header('Refresh: 3; URL=login.php');
                    die();
                }
            } catch (Exception $e) {
                echo '<br><div class="alert alert-danger">
                <strong>Errore!</strong> ' . $e->getMessage() .
                    '</div><br>';
            }
            ?>

            <!-- form di registrazione -->

            <a href="index.php"><img class="mb-4" src="img/logo.svg" alt="" width="150"></a>
            <br>

            <h1 class="h3 mb-3 fw-normal">Nuovo Utente</h1>

            <div class="form-floating">
                <input name="mail" type="email" maxlength="255" class="form-control" id="floatingInput" placeholder="E-Mail" required>
            </div>
            <div class="form-floating">
                <input name="password1" type="password" minlength="6" maxlength="15" class="form-control" id="floatingInput" placeholder="Password" required>
            </div>
            <div class="form-floating">
                <input name="password2" type="password" minlength="6" maxlength="15" class="form-control" id="floatingInput" placeholder="Ripeti Password" required>
            </div>
            <div class="form-floating">
                <input name="name" type="text" maxlength="45" class="form-control" id="floatingInput" placeholder="Nome" required>
            </div>
            <div class="form-floating">
                <input name="surname" type="text" maxlength="45" class="form-control" id="floatingInput" placeholder="Cognome" required>
            </div>
            <div class="form-floating">
                <input name="address" type="text" maxlength="100" class="form-control" id="floatingInput" placeholder="Indirizzo">
            </div>

            <div class="form-floating">
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" class="form-control lastElement" id="floatingInput">
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Registra</button>
            <a href="login.php" class="w-100 btn btn-lg btn-link" role="button">Accedi</a>
        </form>


</body>

</html>