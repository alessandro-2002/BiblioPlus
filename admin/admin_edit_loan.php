<?php
//funzione per stampare i titoli del prestito, creo per ottimizzare l'if
function stampaTitoli($pdo, int $idLoan)
{
    //query di per trovare i titoli del prestito
    $query = "SELECT book.ISBN AS ISBN, title, copy.idCopy AS idCopy
    FROM book, copy, borrow
    WHERE book.ISBN = copy.ISBN
        AND copy.idCopy = borrow.idCopy
        AND borrow.idLoan = :idLoan";

    /* ricerca dei titoli */

    //array di valori da passare per la query titoli
    $values = array(':idLoan' => $idLoan);

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

    //fetch
    $titoli = $res->fetchAll();

?>
    <div class="form-group row">
        <label for="" class="col-4 col-form-label">Titoli</label>
        <div class="col-8">
            <div class="input-group">
                <?php
                //foreach e stampa titoli
                foreach ($titoli as $titolo) {

                    echo '<a href="book_detail.php?ISBN=' . $titolo['ISBN'] . '" class="list-group-item list-group-item-action">' . htmlentities($titolo['title']) . " (" . $titolo['idCopy'] . ')</a>';
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">

    <script>
        //conferma cancellazione prestito
        function confirmDelete() {
            if (confirm("Vuoi eliminare definitivamente il prestito dallo storico? L'azione è irreversibile!")) {
                window.location = "admin_edit_loan.php?idLoan=<?php echo $_GET['idLoan']; ?>&action=delete";
            }
        }
    </script>

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

        //controllo se c'è action in get e ha acl e in tal caso la eseguo
        if (isset($_GET['action']) && $adminAccount->getACLloan()) {

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
            } else if ($_GET['action'] == "delete") {

                //query per cancellazione prestito
                $query = "DELETE FROM loan 
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

                //messaggio di conferma
                echo '<br><div class="alert alert-success">
                            <strong>Prestito eliminato con successo!</strong> Verrai reindirizzato alla lista prestiti.' .
                    '</div>';

                //redirect a pagina prestiti
                header("Refresh:2; URL=admin_loans.php");
                die();
            }

            //infine ricarico la pagina togliendo la parte get
            header("Location: admin_edit_loan.php?idLoan=" . $_GET['idLoan']);
            die();

            //se sta aggiornando la scadenza la aggiorno e poi aggiorno la pagine
        } else if (isset($_POST['duration']) && $_POST['duration'] != NULL && $adminAccount->getACLloan()) {
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
                WHERE loan.idUser = user.idUser
                    AND idLoan = :idLoan";

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
                <div class="text-center">
                    <h3>
                        <?php
                        $isRiconsegnato = false;

                        //se libro è già stato riconsegnato contrassegno come riconsegnato
                        if ($loan['returnDate'] != NULL) {
                            echo '<span class="badge badge-success">RICONSEGNATO</span>';
                            $isRiconsegnato = true;

                            //se non riconsegnato controllo se scaduto
                        } else if (strtotime("now") > strtotime($loan['expireDate'])) {
                            echo '<span class="badge badge-danger">SCADUTO</span>';

                            //se non scaduto né riconsegnato è ancora in prestito
                        } else {
                            echo '<span class="badge badge-warning">IN CORSO</span>';
                        }
                        ?>
                    </h3>
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

                        //stampo titoli
                        stampaTitoli($pdo, $loan['idLoan']);

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

                        //stampo titoli
                        stampaTitoli($pdo, $loan['idLoan']);

                        //stampo pulsanti solo se ha le acl
                        if ($adminAccount->getACLloan()) {
                        ?>
                            <div id="buttons">
                                <div class="form-group row">
                                    <div class="offset-4 col-8">
                                        <button name="submit" type="submit" class="btn btn-primary" style="width: 160px;">Aggiorna scadenza</button>
                                        <button name="submit" type="reset" class="btn" style="width: 160px; background-color: #ffd900;">Reset</button>
                               
                                        <a href="admin_edit_loan.php?idLoan=<?php echo $loan['idLoan']; ?>&action=close" class="btn btn-success" role="button" style="width: 160px;">Riconsegna</a>
                                        <button type="button" onclick="confirmDelete()" class="btn btn-danger" role="button" style="width: 160px;">ELIMINA</a>
                                    </div>
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