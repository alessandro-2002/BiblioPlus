<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>Biblio+</title>
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

        <?php
        //in caso ci sia modifica in post edito l'account
        if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['mail'])) {

            //invoco la funzione per l'edit dell'utente
            try {
                $account->editAccount($account->getId(), $_POST['name'], $_POST['surname'], $_POST['mail'], array('address' => $_POST['address']));

                //stampo successo e ricarico pagina
                echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Modifiche apportate con successo, la pagina verr&agrave; ricaricata.
                    </div>';

                header('Refresh: 3');
                die();
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">
                        <strong>Errore!</strong> ' . $e->getMessage() . '
                    </div>';
            }

            //in caso ci sia modifica all'avatar edito l'avatar
        } else if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            try {
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

                //aggiorno
                $account->editAvatar($account->getId(), $_FILES['avatar']);

                //stampo successo e ricarico pagina
                echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Modifiche apportate con successo, la pagina verr&agrave; ricaricata.
                    </div>';

                header('Refresh: 3');
                die();
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">
                    <strong>Errore!</strong> ' . $e->getMessage() . '
                </div>';
            }

            //in caso si stia rimuovendo l'avatar
        } else if (isset($_POST['removeAvatar'])) {
            try {

                //invoco l'apposita funzione
                $account->removeAvatar($account->getId());

                //stampo successo e ricarico pagina
                echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Modifiche apportate con successo, la pagina verr&agrave; ricaricata.
                    </div>';

                header('Refresh: 3');
                die();
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">
                    <strong>Errore!</strong> ' . $e->getMessage() . '
                </div>';
            }
        }

        //in caso la modifica sia andata a buon fine la pagina viene ricaricata e poi die()
        //in caso di errore, si stampa l'errore e si lascia stampare il resto della pagina

        ?>

        <div class="container">
            <h1>Profilo Utente</h1>
            <hr>
            <div class="row">
                <!-- left column -->
                <div class="col-md-3">
                    <div class="text-center">
                        <img src="avatars/<?php
                                            if ($account->getAvatar() != NULL) {
                                                echo htmlentities($account->getAvatar());
                                            } else {
                                                echo "no-avatar.jpg";
                                            }
                                            ?>" class="avatar img-circle" alt="avatar">
                        <h6>Aggiorna o elimina il tuo avatar...</h6>
                        <form id="form" action="" method="post" enctype="multipart/form-data">
                            <input type="file" name="avatar" class="form-control">
                            <br>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-3">
                                    <input type="submit" class="btn btn-primary" value="Aggiorna">
                                    <span></span>
                                    <input type="submit" class="btn btn-default" name="removeAvatar" value="Rimuovi">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- edit form column -->
                <div class="col-md-9 personal-info">
                    <h3>Informazioni personali</h3>

                    <form class="form-horizontal" method="POST" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Id Utente:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="text" value="<?php
                                                                                echo htmlentities($account->getId());
                                                                                ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nome:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="text" maxlength="45" name="name" value="<?php
                                                                                                            echo htmlentities($account->getName());
                                                                                                            ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Cognome:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="text" maxlength="45" name="surname" value="<?php
                                                                                                                echo htmlentities($account->getSurname());
                                                                                                                ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Email:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="email" maxlength="255" name="mail" value="<?php
                                                                                                            echo htmlentities($account->getMail());
                                                                                                            ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Indirizzo:</label>
                            <div class="col-lg-8">
                                <input class="form-control" type="text" maxlength="100" name="address" value="<?php
                                                                                                                echo htmlentities($account->getAddress());
                                                                                                                ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-8">
                                <input type="submit" class="btn btn-primary" value="Salva">
                                <span></span>
                                <input type="reset" class="btn btn-default" value="Reset">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

</body>

</html>