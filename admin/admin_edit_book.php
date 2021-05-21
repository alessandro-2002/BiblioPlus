<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/loans.css">
    <title>Area Bibliotecario</title>

    <script>
        //conferma cancellazione libro
        function confirmDelete() {
            if (confirm("Vuoi eliminare DEFINITIVAMENTE il libro dal catalogo?")) {
                if (confirm("Sei sicuro? L'azione è irreversibile e comporta l'eliminazione di tutto lo storico prestiti dell'utente!")) {
                    window.location = "admin_edit_user.php?ISBN=<?php echo $_GET['ISBN']; ?>&action=delete";
                }
            }
        }
    </script>
</head>

<body>

    <div class="content">
        <?php

        //importo header
        require_once('../assets/admin_header.php');

        //controllo che ci sia ISBN in get altrimenti redirect
        if (!isset($_GET['ISBN']) || $_GET['ISBN'] == NULL) {
            header('Location: admin_books.php');
        }

        //controllo se bibliotecario ha le ACL per la modifica altrimenti segnalo che può solo visualizzare
        if (!$adminAccount->getACLcatalogue()) {
            echo '<br><div class="alert alert-info">
                    <strong>Info!</strong> Non disponi dell\'autorizzazione per modificare il catalogo, questa scheda è in sola lettura.
                </div>';
        }

        /* ricerca ISBN e dati libro */

        //query di per trovare libro
        $query = "SELECT book.ISBN AS ISBN, title, subtitle, language, year, idPublisher, cover
            FROM book
            WHERE book.ISBN = :ISBN";


        //array di valori da passare
        $values = array(':ISBN' => $_GET['ISBN']);

        /* esecuzione query */

        try {

            //prepare query, ottimizza l'esecuzione
            $res = $pdo->prepare($query);

            //esecuzione con passaggio di valori 
            $res->execute($values);
        } catch (PDOException $e) {
            echo $e->getMessage();

            //in caso di errore stampo con stile
            echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
            die();
        }

        //controllo esistenza di un libro
        if ($res->rowCount() == 1) {

            //controllo se bibliotecario ha le ACL per la modifica altrimenti segnalo che può solo visualizzare
            if (!$adminAccount->getACLcatalogue()) {
                echo '<br><div class="alert alert-info">
                        <strong>Info!</strong> Non disponi dell\'autorizzazione per modificare i libri, questa scheda è in sola lettura.
                        </div>';

                //se ha acl controllo se c'è action in corso
            } else {
                if (isset($_GET['action'])) {

                    //controllo se sta eliminando libro
                    if ($_GET['action'] == "deleteBook") {

                        /* eliminazione libro */

                        //query di per trovare libro
                        $query = "DELETE FROM book
                                WHERE book.ISBN = :ISBN";


                        //array di valori da passare
                        $values = array(':ISBN' => $_GET['ISBN']);

                        /* esecuzione query */

                        try {

                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione con passaggio di valori 
                            $res->execute($values);

                            //messaggio di conferma
                            echo '<br><div class="alert alert-success">
                                    <strong>Libroeliminato con successo!</strong> Verrai reindirizzato al catalogo.' .
                                '</div>';

                            //redirect a pagina prestiti
                            header("Refresh:2; URL=admin_books.php");
                            die();
                        } catch (PDOException $e) {

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                    <strong>Errore!</strong> Errore nell'eliminazione.
                                </div>";
                            die();
                        }
                    }
                }
            }

            /* **********


             FATTO FINO A QUI


             *********** */

            //in caso ci sia modifica in post edito l'account
            if (isset($_POST['title']) && isset($_POST['idPublisher']) && isset($_POST['mail'])) {

                //invoco la funzione per l'edit dell'utente
                try {
                    //creo array per parametri opzionali indirizzo e enabled
                    $options = array('address' => $_POST['address']);

                    if (isset($_POST['enabled']) && $_POST['enabled'] == "on") {
                        $options['enabled'] = 1;
                    } else {
                        $options['enabled'] = 0;
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

            <!-- VISTA USER -->
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
                                        <input type="submit" class="btn btn-primary" value="Salva" style="width: 160px;">
                                        <span></span>
                                        <input type="reset" class="btn btn-default" value="Reset" style="width: 160px;">

                                        <button type="button" onclick="confirmResetPassword()" class="btn btn-warning" role="button" style="width: 160px;">Nuova Password</button>
                                        <button type="button" onclick="confirmDelete()" class="btn btn-danger" role="button" style="width: 160px;">ELIMINA</button>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>

            <br><br>





        <?php
        } else {
            echo '<br><div class="alert alert-warning">
                        <strong>Attenzione!</strong> Nessun Libro trovato.
                        </div>';
        }

        ?>


    </div>

    <br><br>

</body>

</html>