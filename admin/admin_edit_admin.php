<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/toggleSwitch.css">
    <title>Area Bibliotecario</title>

    <script>
        //conferma cancellazione admin
        function confirmDelete() {
            if (confirm("Vuoi eliminare DEFINITIVAMENTE il bibliotecario?")) {
                if (confirm("Sei sicuro? L'azione è irreversibile!")) {
                    window.location = "admin_edit_admin.php?idAdmin=<?php echo $_GET['idAdmin']; ?>&action=delete";
                }
            }
        }

        //conferma reset password
        function confirmResetPassword() {
            if (confirm("Vuoi resettare la password del bibliotecario?")) {
                window.location = "admin_edit_admin.php?idAdmin=<?php echo $_GET['idAdmin']; ?>&action=resetPassword";
            }
        }
    </script>
</head>

<body>

    <div class="content">
        <?php

        //importo header
        require_once('../assets/admin_header.php');

        //importo classe admin
        require_once("../classes/admin_class.php");

        //controllo se bibliotecario ha le ACL per la modifica degli admin altrimenti segnalo che può solo visualizzare e die
        if (!$adminAccount->getACLadmin()) {
            echo '<br><div class="alert alert-danger">
                    <strong>Errore!</strong> Non disponi dell\'autorizzazione per gestire i Bibliotecari.
                </div>';
            die();
        }

        //controllo che ci sia user in get altrimenti redirect
        if (!isset($_GET['idAdmin']) || $_GET['idAdmin'] == NULL) {
            header('Location: admin_admins.php');
        }

        //creo oggetto e lo popolo da id, se ritorna True procedo altrimenti stampo errore
        $admin = new Admin();

        //controllo che id sia inserito come int e in caso procedo con la popolazione dell'oggetto
        if (((int)$_GET['idAdmin']) != 0 &&  $admin->popolaDaId($_GET['idAdmin'])) {

            //se ha acl e ha popolato controllo se c'è action in corso
            if (isset($_GET['action'])) {

                //controllo se sta eliminando l'admin
                if ($_GET['action'] == "delete") {

                    //elimino account dalla classe
                    try {
                        $admin->deleteAccount($_GET['idAdmin']);

                        //messaggio di conferma
                        echo '<br><div class="alert alert-success">
                                <strong>Bibliotecario eliminato con successo!</strong> Verrai reindirizzato alla lista bibliotecari.' .
                            '</div>';

                        //redirect a pagina admins
                        header("Refresh:2; URL=admin_admins.php");
                        die();
                    } catch (Exception $e) {
                        //messaggio di errore
                        echo '<br><div class="alert alert-danger">
                                <strong>Errore!</strong> ' . $e->getMessage() .
                            '</div>';
                    }
                } else if ($_GET['action'] == "resetPassword") {

                    //resetto password dalla classe
                    try {
                        //salvo nuova password
                        $newPassword = $admin->resetPassword();

                        //messaggio di conferma
                        echo '<script>
                                alert("Password aggiornata con successo! Al prossimo accesso sarà richiesto il reset. La nuova password è: ' . $newPassword . '");
                                window.location = "admin_edit_admin.php?idAdmin=' . $_GET['idAdmin'] . '";' .
                            '</script>';
                    } catch (Exception $e) {
                        //messaggio di errore
                        echo '<br><div class="alert alert-danger">
                                <strong>Errore!</strong> ' . $e->getMessage() .
                            '</div>';
                    }
                }
            }

            //in caso ci sia modifica in post edito l'account
            if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['mail']) && $adminAccount->getACLadmin()) {

                //invoco la funzione per l'edit dell'admin
                try {
                    //creo array per le ACL

                    //catalogo
                    if (isset($_POST['ACLcatalogue']) && $_POST['ACLcatalogue'] == "on") {
                        $ACL['catalogue'] = 1;
                    } else {
                        $ACL['catalogue'] = 0;
                    }

                    //prestiti
                    if (isset($_POST['ACLloan']) && $_POST['ACLloan'] == "on") {
                        $ACL['loan'] = 1;
                    } else {
                        $ACL['loan'] = 0;
                    }

                    //utenti
                    if (isset($_POST['ACLuser']) && $_POST['ACLuser'] == "on") {
                        $ACL['user'] = 1;
                    } else {
                        $ACL['user'] = 0;
                    }

                    //bibliotecari
                    if (isset($_POST['ACLadmin']) && $_POST['ACLadmin'] == "on") {
                        $ACL['admin'] = 1;
                    } else {
                        $ACL['admin'] = 0;
                    }

                    $admin->editAccount($admin->getId(), $_POST['name'], $_POST['surname'], $_POST['mail'], $ACL);

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

            <!-- VISTA ADMIN -->
            <div class="container">
                <h1>Profilo Bibliotecario</h1>
                <hr>

                <form class="form-horizontal" method="POST" action="">
                    <div class="form-group row">
                        <label class="col-4 col-form-label">Id Bibliotecario</label>
                        <div class="col-8">
                            <input class="form-control" type="text" value="<?php
                                                                            echo htmlentities($admin->getId());
                                                                            ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label">Nome</label>
                        <div class="col-8">
                            <input class="form-control" type="text" maxlength="45" name="name" value="<?php
                                                                                                        echo htmlentities($admin->getName());
                                                                                                        ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label">Cognome</label>
                        <div class="col-8">
                            <input class="form-control" type="text" maxlength="45" name="surname" value="<?php
                                                                                                            echo htmlentities($admin->getSurname());
                                                                                                            ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input class="form-control" type="email" maxlength="255" name="mail" value="<?php
                                                                                                        echo htmlentities($admin->getMail());
                                                                                                        ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL catalogo</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLcatalogue" <?php
                                                                            if ($admin->getACLcatalogue()) {
                                                                                echo " checked";
                                                                            }
                                                                            ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL prestiti</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLloan" <?php
                                                                        if ($admin->getACLloan()) {
                                                                            echo " checked";
                                                                        }
                                                                        ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL Utenti</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLuser" <?php
                                                                        if ($admin->getACLuser()) {
                                                                            echo " checked";
                                                                        }
                                                                        ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL Bibliotecari</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLadmin" <?php
                                                                        if ($admin->getACLadmin()) {
                                                                            echo " checked";
                                                                        }
                                                                        ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <!-- bottoni -->
                    <div class="form-group row">
                        <label class="col-4 col-form-label"></label>
                        <div class="col-8">
                            <input type="submit" class="btn btn-primary" value="Salva" style="width: 160px;">
                            <span></span>
                            <input type="reset" class="btn btn-default" value="Reset" style="width: 160px;">

                            <button type="button" onclick="confirmResetPassword()" class="btn btn-warning" role="button" style="width: 160px;">Nuova Password</button>
                            <button type="button" onclick="confirmDelete()" class="btn btn-danger" role="button" style="width: 160px;">ELIMINA</button>

                        </div>
                    </div>
                </form>
            </div>
    </div>


<?php
        } else {
            echo '<br><div class="alert alert-warning">
                    <strong>Attenzione!</strong> Nessun bibliotecario trovato.
                </div>';
        }
?>


</div>

<br><br>

</body>

</html>