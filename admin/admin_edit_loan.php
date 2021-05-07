<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/admin_edit_loan.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Area Bibliotecario</title>
</head>

<body>

    <div class="content">
        <?php

        //importo header
        require_once('../assets/admin_header.php');

        //controllo che ci sia loan in get altrimenti redirect
        if (!isset($_GET['idLoan']) || $_GET['idLoan'] == NULL) {
            header('Location: loans.php');  //fare redirect corretto o messaggio di errore
        }

        //controllo se bibliotecario ha le ACL per la modifica altrimenti segnalo che può solo visualizzare
        if (!$adminAccount->getACLloan()) {
            echo '<br><div class="alert alert-info">
                        <strong>Info!</strong> Non disponi dell\'autorizzazione per gestire i prestiti, questa scheda è in sola lettura.
                        </div>';
        }

        //controllo se c'è action in get e in tal caso la eseguo
        if (isset($_GET['action'])) {

            //controllo se l'azione in get è close per chiudere il prestito
            if ($_GET['action'] == "close") {

                //inserisco nel where che la returnDate deve essere NULL altrimenti non lascio chiudere il prestito
                $query = "UPDATE loan 
                    SET returnDate = NOW()
                    WHERE idLoan = :idLoan
                        AND returnDate IS NULL";

                //array di valori
                $values = array(':idLoan' => $_GET['idLoan']);

                try {
                    //preparo query
                    $res = $pdo->prepare($query);

                    //eseguo query
                    $res->execute($values);
                } catch (PDOException $e) {
                    //in caso di eccezione ritorno l'eccezione
                    throw new Exception('Database query error');
                }

                //controllo se l'azione in get è la riapertura di un prestito chiuso, annullo quindi la data di riconsegna
            } else if ($_GET['action'] == "reopen") {

                //query per settare a NULL la data di riconsegna
                $query = "UPDATE loan 
                    SET returnDate = NULL
                    WHERE idLoan = :idLoan";

                //array di valori
                $values = array(':idLoan' => $_GET['idLoan']);

                try {
                    //preparo query
                    $res = $pdo->prepare($query);

                    //eseguo query
                    $res->execute($values);
                } catch (PDOException $e) {
                    //in caso di eccezione ritorno l'eccezione
                    throw new Exception('Database query error');
                }
            }

            //infine ricarico la pagina togliendo la parte get
            header("Location: admin_edit_loan.php?idLoan=" . $_GET['idLoan']);
            die();

            //se sta aggiornando la scadenza la aggiorno e poi aggiorno la pagine
        } else if (isset($_POST['duration']) && $_POST['duration'] != NULL) {
            $query = "UPDATE loan 
                    SET duration = :duration
                    WHERE idLoan = :idLoan";

            //array di valori
            $values = array(':idLoan' => $_GET['idLoan'], ':duration' => $_POST['duration']);

            try {
                //preparo query
                $res = $pdo->prepare($query);

                //eseguo query
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione
                throw new Exception('Database query error');
            }

            header("Refresh: 0");
            die();
        }

        //prendo da db dati sul prestito
        //get prestitO (idPrestito, dataInizio, dataScadenza, durata, dataRestituzione), tutte le date sono formattate da DB
        $query = "SELECT idLoan,
                    DATE_FORMAT(loanDate, '%d-%m-%Y %H:%i') AS loanDate, 
                    DATE_FORMAT(DATE_ADD(loanDatE, INTERVAL duration DAY), '%d-%m-%Y %H:%i') AS expireDate, 
                    duration,
                    DATE_FORMAT(returnDate, '%d-%m-%Y %H:%i') AS returnDate,
                    user.idUser, user.name, user.surname 
                FROM loan, user
                WHERE idLoan = :idLoan";

        //array di valori
        $values = array(':idLoan' => $_GET['idLoan']);


        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di eccezione ritorno l'eccezione
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $loan = $res->fetch(PDO::FETCH_ASSOC);

        //se esiste il risultato stampo la pagina normale altrimenti errore
        if (is_array($loan)) {
        ?>

            <!-- VISTA PRESTITO -->
            <div class="container">
                <h1>Prestito id <?php echo $loan['idLoan']; ?></h1>

                <hr>
                <div id="status">
                    <?php
                    $isRiconsegnato = false;

                    //se libro è già stato riconsegnato contrassegno come riconsegnato
                    if ($loan['returnDate'] != NULL) {
                        echo "<span class='returned'> RICONSEGNATO </span>";
                        $isRiconsegnato = true;

                        //se non riconsegnato controllo se scaduto
                    } else if (strtotime("now") > strtotime($loan['expireDate'])) {
                        echo "<span class='expired'> SCADUTO </span>";

                        //se non scaduto né riconsegnato è ancora in prestito
                    } else {
                        echo "<span class='inLoan'> IN CORSO </span>";
                    }
                    ?>
                </div>

                <br>

                <form action="" method="POST">
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Utente</label>
                        <div class="col-8">
                            <div class="input-group">
                                <p class="form-control">
                                    <?php echo '<a href="admin_edit_user.php?idUser=' . $loan['idUser'] . '">' . $loan['name'] . ' ' . $loan['surname'] . '</a>'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Data di inizio</label>
                        <div class="col-8">
                            <div class="input-group">
                                <p class="form-control">
                                    <?php echo $loan['loanDate']; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php
                    //se è riconsegnato stampo solo la data di riconsegna e il bottone per riaprire il prestito
                    if ($isRiconsegnato) {
                    ?>
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label">Data di riconsegna</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <p class="form-control">
                                        <?php echo $loan['returnDate']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php
                        //controllo se ha acl, in tal caso stampo il bottone per riaprire il prestito
                        if ($adminAccount->getACLloan()) {
                        ?>
                            <div class="form-group row">
                                <div class="offset-4 col-8">
                                    <a href="admin_edit_loan.php?idLoan=<?php echo $loan['idLoan']; ?>&action=reopen" class="btn btn-success" role="button">Annulla riconsegna</a>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label">Data di scadenza</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <p class="form-control">
                                        <?php echo $loan['expireDate']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="duration" class="col-4 col-form-label">Durata massima (giorni)</label>
                            <div class="col-8">
                                <input id="duration" name="duration" type="number" min="1" step="1" class="form-control" value="<?php echo $loan['duration']; ?>" <?php

                                                                                                                                                                    //stampo disabled se non ha le acl
                                                                                                                                                                    if (!$adminAccount->getACLloan()) {
                                                                                                                                                                        echo " disabled";
                                                                                                                                                                    }
                                                                                                                                                                    ?>>
                            </div>
                        </div>
                        <?php

                        //stampo pulsanti solo se ha le acl
                        if ($adminAccount->getACLloan()) {
                        ?>
                            <div class="form-group row">
                                <div class="offset-4 col-8">
                                    <button name="submit" type="submit" class="btn btn-primary">Aggiorna scadenza</button>
                                    <button name="submit" type="reset" class="btn">Reset</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-4 col-8">
                                    <a href="admin_edit_loan.php?idLoan=<?php echo $loan['idLoan']; ?>&action=close" class="btn btn-success" role="button">Riconsegna</a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </form>

            </div>

        <?php

        } else {
            //in caso non ci sia nessun prestito warning
            echo '<br><div class="alert alert-warning">
                        <strong>Attenzione!</strong> Nessun prestito trovato.
                    </div>';
        }

        ?>

        <br><br>



    </div>
</body>

</html>