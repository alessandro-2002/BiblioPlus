<!-- cambiamento di password per l'utente loggato 
!= dal reset -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/change_password.css">
    <title>Biblioteca facile!</title>
</head>

<body>

    <div class="content">
        <?php
        require_once('assets/header.php');

        //controllo che ci sia autenticazione o faccio redirect
        try {
            if (!$account->isAuthenticated()) {
                header('Location: /');
                die();

                //altrimenti controllo se sta facendo cambio in post
            } else if (isset($_POST['oldPassword']) && isset($_POST['newPassword1']) && isset($_POST['newPassword2'])) {
                $cambio = $account->changePassword($_POST['oldPassword'], $_POST['newPassword1'], $_POST['newPassword2']);

                if ($cambio) {
                    echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Cambio password effettuato con successo, verrai reindirizzato alla home.
                        </div>';

                    header('Refresh: 2; URL=/');
                    die();
                }
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">
                <strong>Errore!</strong> ' . $e->getMessage() . '
            </div>';
        }

        //controllo se il cambio è per scadenza della password
        $expirationDate = new DateTime($account->getExpiration());
        $now = new DateTime();

        //controllo scadenza
        if ($expirationDate < $now) {
            //stampo alert
            echo "<div class=\"alert alert-warning\">
                    <strong>Attenzione!</strong> La tua password &egrave; scaduta, rinnovala.
                </div>";
        }

        ?>

        <h1>Cambio credenziali</h1>

        <!-- form di cambio password -->
        <form id="form" action="change_password.php" method="post">
            <label for="oldPassword">Password attuale: </label>
            <input id="txt" type="password" placeholder="Password attuale" name="oldPassword" required><br>

            <label for="newPassword1">Nuova password: </label>
            <input id="txt" type="password" placeholder="Nuova password" name="newPassword1" required><br>

            <label for="newPassword2">Ripeti la nuova password: </label>
            <input id="txt" type="password" placeholder="Nuova password" name="newPassword2" required><br>
            <hr>
            <input type="submit" id="button" value="Modifica">
        </form>




    </div>

</body>

</html>