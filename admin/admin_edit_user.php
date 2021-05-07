<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/toggleSwitch.css">
    <link rel="stylesheet" href="../css/loans.css">
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

            <br><br>

            <!-- PRESTITI IN CORSO DELL'UTENTE -->

            <h1>Prestiti</h1>

            <?php
            //get prestiti (idPrestito, dataInizio, dataScadenza, dataRestituzione), tutte le date sono formattate da DB
            $query = "SELECT idLoan, 
                    DATE_FORMAT(loanDate, '%d-%m-%Y %H:%i') AS loanDate, 
                    DATE_FORMAT(DATE_ADD(loanDatE, INTERVAL duration DAY), '%d-%m-%Y %H:%i') AS expireDate, 
                    DATE_FORMAT(returnDate, '%d-%m-%Y %H:%i') AS returnDate 
                FROM loan
                WHERE idUser = :idUser
                ORDER BY loanDate DESC";

            $values = array(':idUser' => $user->getId());

            /* esecuzione query */
            try {
                //prepare query
                $res = $pdo->prepare($query);

                //esecuzione con passaggio di valori
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di errore stampo con stile
                echo $e->getMessage();
                echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
                die();
            }



            //controllo se ci sono prestiti
            if ($res->rowCount() > 0) {

                //fetch
                $loans = $res->fetchAll();

            ?>

                <!-- Visualizzazione tabellare dei prestiti -->
                <div class="loans">

                    <table>

                        <!-- intestazione -->
                        <tr>
                            <th>
                                Id Prestito
                            </th>
                            <th>
                                Data
                            </th>
                            <th>
                                Scadenza
                            </th>
                            <th>
                                Riconsegna
                            </th>
                            <th>
                                Titoli
                            </th>
                            <th>
                                Stato
                            </th>
                        </tr>

                        <?php


                        //query di base per trovare i titoli del prestito
                        $query = "SELECT book.ISBN AS ISBN, title, copy.idCopy AS idCopy
                    FROM book, copy, borrow
                    WHERE book.ISBN = copy.ISBN
                        AND copy.idCopy = borrow.idCopy
                        AND borrow.idLoan = :idLoan";

                        //prepare query, ottimizza l'esecuzione
                        $res = $pdo->prepare($query);

                        //stampa dei prestiti in tabella
                        foreach ($loans as $loan) {

                            /* ricerca dei titoli */

                            //array di valori da passare per la query titoli
                            $values = array(':idLoan' => $loan['idLoan']);

                            /* esecuzione query */

                            try {
                                //esecuzione con passaggio di eventuali valori di ricerca, controllo esistano

                                $res->execute($values);

                                //fetch
                                $titoli = $res->fetchAll();
                            } catch (PDOException $e) {
                                echo $e->getMessage();

                                //in caso di errore stampo con stile
                                echo "<div class=\"alert alert-danger\">
                                <strong>Errore!</strong> Errore nella ricerca
                                </div>";
                                die();
                            }


                            /* stampa tabellare */

                            echo "<tr>";

                            //id prestito
                            echo "<td><a href='admin_edit_loan.php?idLoan=" . $loan['idLoan'] . "'>" . $loan['idLoan'] . "</a></td>";

                            //date
                            echo "<td>" . $loan['loanDate'] . "</td>";

                            //scadenza
                            echo "<td>" . $loan['expireDate'] . "</td>";

                            //riconsegna
                            if ($loan['returnDate'] == NULL) {
                                echo "<td> - </td>";
                            } else {
                                echo "<td>" . $loan['returnDate'] . "</td>";
                            }

                            //titoli
                            echo "<td>";

                            //stampo i titoli nel prestito andando a capo dopo ognuno
                            //Titolo (idCopia) con link sul titolo alla ricerca per ISBN in index
                            foreach ($titoli as $index => $titolo) {
                                echo '<a href="book_detail.php?ISBN=' . $titolo['ISBN'] . '">' . htmlentities($titolo['title']) . "</a> (" . $titolo['idCopy'] . ')';

                                if (count($titoli) > $index + 1) {
                                    echo "<br>";
                                }
                            }

                            echo "</td>";

                            //stato
                            $now = strtotime("now");

                            //se libro è già stato riconsegnato contrassegno come riconsegnato
                            if ($loan['returnDate'] != NULL) {
                                echo "<td class='returned'> RICONSEGNATO </td>";

                                //se non riconsegnato controllo se scaduto
                            } else if (strtotime("now") > strtotime($loan['expireDate'])) {
                                echo "<td class='expired'> SCADUTO </td>";

                                //se non scaduto né riconsegnato è ancora in prestito
                            } else {
                                echo "<td class='inLoan'> IN CORSO </td>";
                            }


                            echo "</tr>";
                        }
                        ?>


                    </table>

                </div>

            <?php

            } else {
                //in caso non ci sia nessun prestito warning
                echo "<div class=\"alert alert-warning\">
                    <strong>Attenzione!</strong> Ancora nessun prestito inserito nel sistema.
                </div>";
            }
            ?>


        <?php
        } else {
            echo '<br><div class="alert alert-warning">
                        <strong>Attenzione!</strong> Nessun utente trovato.
                        </div>';
        }
        ?>


    </div>

    <br><br>

</body>

</html>