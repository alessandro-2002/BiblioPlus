<!-- cambiamento di password per l'utente loggato 
!= dal reset -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../css/base.css">

    <title>Area Bibliotecario</title>
</head>

<body>

    <div class="content">
        <?php
        require_once('../assets/admin_header.php');

        try {
            //altrimenti controllo se sta facendo cambio in post
            if (isset($_POST['oldPassword']) && isset($_POST['newPassword1']) && isset($_POST['newPassword2'])) {
                $cambio = $adminAccount->changePassword($_POST['oldPassword'], $_POST['newPassword1'], $_POST['newPassword2']);

                if ($cambio) {
                    echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Cambio password effettuato con successo, verrai disconnesso.
                        </div>';

                    header('Refresh: 2; URL=index.php');
                    die();
                }
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">
                <strong>Errore!</strong> ' . $e->getMessage() . '
            </div>';
        }

        //controllo se il cambio è per scadenza della password
        $expirationDate = new DateTime($adminAccount->getExpiration());
        $now = new DateTime();

        //controllo scadenza
        if ($expirationDate < $now) {
            //stampo alert
            echo "<div class=\"alert alert-warning\">
                    <strong>Attenzione!</strong> La tua password &egrave; scaduta, rinnovala.
                </div>";
        }

        ?>

        <div class="container">

            <h1>Cambio credenziali Bibliotecario</h1>

            <hr>

            <form action="" method="POST">
                <div class="form-group row">
                    <label for="oldPassword" class="col-4 col-form-label">Password attuale</label>
                    <div class="col-8">
                        <div class="input-group">
                            <input id="oldPassword" name="oldPassword" type="password" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-4 col-form-label">Nuova password</label>
                    <div class="col-8">
                        <div class="input-group">
                            <input id="newPassword1" name="newPassword1" type="password" minlength="6" maxlength="15" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div id="copie">
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Ripeti la nuova password</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input id="newPassword2" name="newPassword2" type="password" minlength="6" maxlength="15" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <button type="submit" class="btn btn-primary">Modifica</button>
                    </div>
                </div>

            </form>
        </div>




    </div>

</body>

</html>