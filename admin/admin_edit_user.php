<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/toggleSwitch.css">
    <title>Area Bibliotecario</title>
</head>

<body>

    <div class="content">
        <?php

        //importo header
        require_once('../assets/admin_header.php');

        //importo classe user
        require_once("../classes/user_class.php");

        //controllo che ci sia user in get altrimenti redirect
        if (!isset($_GET['idUser']) || $_GET['idUser'] == NULL) {
            header('Location: admin_users.php');
        }

        //creo oggetto e lo popolo da id, se ritorna True procedo altrimenti stampo errore
        $user = new User();

        //controllo che id sia inserito come int e in caso procedo con la popolazione dell'oggetto
        if (((int)$_GET['idUser']) != 0 &&  $user->popolaDaId($_GET['idUser'])) {

            //controllo se bibliotecario ha le ACL per la modifica altrimenti segnalo che può solo visualizzare
            if (!$adminAccount->getACLuser()) {
                echo '<br><div class="alert alert-info">
                        <strong>Info!</strong> Non disponi dell\'autorizzazione per modificare gli utenti, questa scheda è in sola lettura.
                        </div>';
            }


            //in caso ci sia modifica in post edito l'account
            if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['mail']) && $adminAccount->getACLuser()) {

                //invoco la funzione per l'edit dell'utente
                try {
                    //creo array per parametri opzionali indirizzo e enabled
                    $options = array('address' => $_POST['address']);

                    if (isset($_POST['enabled']) && $_POST['enabled'] == "on") {
                        $options['enabled'] = true;
                    } else {
                        $options['enabled'] = false;
                    }

                    $user->editAccount($user->getId(), $_POST['name'], $_POST['surname'], $_POST['mail'], $options);

                    //stampo successo e ricarico pagina
                    echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Modifiche apportate con successo, la pagina verr&agrave; ricaricata.
                    </div>';

                    //in caso la modifica sia andata a buon fine la pagina viene ricaricata e poi die()
                    header('Refresh: 3');
                    die();


                    //in caso di errore, si stampa l'errore e si lascia stampare il resto della pagina
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">
                        <strong>Errore!</strong> ' . $e->getMessage() . '
                    </div>';
                }
            }



        ?>

            <div class="container">
                <h1>Profilo Utente</h1>
                <hr>
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-3">
                        <div class="text-center">
                            <img src="../avatars/<?php
                                                    if ($user->getAvatar() != NULL) {
                                                        echo htmlentities($user->getAvatar());
                                                    } else {
                                                        echo "no-avatar.jpg";
                                                    }
                                                    ?>" class="avatar img-circle" alt="avatar">
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
                                                                                    echo htmlentities($user->getId());
                                                                                    ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nome:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" maxlength="45" name="name" value="<?php
                                                                                                                echo htmlentities($user->getName());
                                                                                                                ?>" required<?php
                                                                                                                            //se non ha autorizzazione metto disabled
                                                                                                                            if (!$adminAccount->getACLuser()) {
                                                                                                                                echo " disabled";
                                                                                                                            }
                                                                                                                            ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Cognome:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" maxlength="45" name="surname" value="<?php
                                                                                                                    echo htmlentities($user->getSurname());
                                                                                                                    ?>" required<?php
                                                                                                                                //se non ha autorizzazione metto disabled
                                                                                                                                if (!$adminAccount->getACLuser()) {
                                                                                                                                    echo " disabled";
                                                                                                                                }
                                                                                                                                ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Email:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="email" maxlength="255" name="mail" value="<?php
                                                                                                                echo htmlentities($user->getMail());
                                                                                                                ?>" required<?php
                                                                                                                            //se non ha autorizzazione metto disabled
                                                                                                                            if (!$adminAccount->getACLuser()) {
                                                                                                                                echo " disabled";
                                                                                                                            }
                                                                                                                            ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Indirizzo:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" maxlength="100" name="address" value="<?php
                                                                                                                    echo htmlentities($user->getAddress());
                                                                                                                    ?>" <?php
                                                                                                                        //se non ha autorizzazione metto disabled
                                                                                                                        if (!$adminAccount->getACLuser()) {
                                                                                                                            echo " disabled";
                                                                                                                        }
                                                                                                                        ?>>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Abilitato:</label>
                                <div class="col-lg-8">
                                    <label class="switch">
                                        <input type="checkbox" name="enabled" <?php
                                                                                if ($user->isEnabled()) {
                                                                                    echo " checked";
                                                                                }
                                                                                ?> <?php
                                                                                    //se non ha autorizzazione metto disabled
                                                                                    if (!$adminAccount->getACLuser()) {
                                                                                        echo " disabled";
                                                                                    }
                                                                                    ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <?php
                            //se ha autorizzazione stampo form per modifica utente
                            if ($adminAccount->getACLuser()) {
                            ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" class="btn btn-primary" value="Salva">
                                        <span></span>
                                        <input type="reset" class="btn btn-default" value="Reset">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>

        <?php
        } else {
            echo '<br><div class="alert alert-warning">
                        <strong>Attenzione!</strong> Nessun utente trovato.
                        </div>';
        }
        ?>


    </div>

</body>

</html>