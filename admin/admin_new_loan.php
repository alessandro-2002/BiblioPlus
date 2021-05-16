<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/loans.css">

    <script src="../js/admin_new_loan.js"></script>


    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");

        //controllo ACL e se non le ha non lascio l'accesso alla pagina, stampo errore
        if ($adminAccount->getACLloan()) {

            //controllo se in post sta inserendo prestito
            if (isset($_POST['newLoan'])) {
                //controllo siano stati inseriti i dati
                if (isset($_POST['idUser']) && isset($_POST['duration']) && isset($_POST['idCopy'])) {

                    //controlli per la correttezza dei dati inseriti, lavoro in un unico blocco try-catch
                    try {
                        /* controllo esistenza utente */

                        //query di ricerca per utente esistente e abilitato
                        $query = "SELECT idUser
                                FROM user
                                WHERE idUser = :idUser
                                    AND isEnabled";

                        //array di valori da passare
                        $values = array(':idUser' => $_POST['idUser']);

                        // esecuzione query 
                        try {
                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione con passaggio di valori
                            $res->execute($values);
                        } catch (PDOException $e) {
                            throw new Exception("Query error");
                        }

                        //controllo se non c'è un utente, in tal caso lancio eccezione altrimenti continuo
                        if ($res->rowCount() != 1) {
                            throw new Exception("Utente inesistente o non abilitato.");
                        }


                        /* controllo esistenza e che le copie non siano in prestito */

                        //query di ricerca base per copie esistenti e non in prestito
                        $query = "SELECT idCopy
                                FROM copy
                                WHERE idCopy NOT IN (SELECT idCopy
                                                    FROM borrow, loan
                                                    WHERE borrow.idLoan = loan.idLoan
                                                        AND loan.returnDate IS NULL)
                                    AND idCopy IN (";

                        //scorro la lista di idCopy dal post per inserirle nella query
                        foreach ($_POST['idCopy'] as $index => $idCopy) {
                            //concateno nella stringa i vari idCopy
                            $query = $query . $idCopy;

                            //se non è ultimo elemento metto ,
                            if (count($_POST['idCopy']) > $index + 1) {
                                $query = $query . ", ";
                            }
                        }

                        //alla fine chiudo query
                        $query = $query . ")";

                        // esecuzione query 
                        try {
                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione
                            $res->execute();
                        } catch (PDOException $e) {
                            throw new Exception("Query error");
                        }

                        //fetch
                        $res = $res->fetchAll();
                        //prendo solo la colonna idCopy in modo da creare un unico vettore
                        $res = array_column($res, 'idCopy');

                        //trovo le differenze tra i 2 array (elementi inesistenti o già in prestito)
                        $diff = array_diff($_POST['idCopy'], $res);

                        //controllo se ci sono id che non sono trovati nella query, quindi la copia esiste ed è libera altrimenti genero eccezione
                        if (count($diff) != 0) {
                            //creo stringa contenente tutte le copie che creano errore
                            $errorCopyString = "";
                            $c = 0;
                            foreach ($diff as $err) {
                                //concateno gli idCopy                                
                                $errorCopyString = $errorCopyString . $err;

                                $c++;

                                //se non è ultimo elemento metto ,
                                if (count($diff) > $c) {
                                    $errorCopyString = $errorCopyString . ", ";
                                }
                            }

                            throw new Exception("Copie id " . $errorCopyString . " inesistenti o gi&agrave; in prestito.");
                        }


                        /* inserimento dati */

                        //effettuato il controllo di tutti i dati procedo con l'inserimento del db mediante transazione
                        try {
                            //inizio transazione
                            $pdo->beginTransaction();

                            /* query per inserimento dati in loan */

                            //inserisco idUser e durata, la data è inserita con default
                            $query = "INSERT INTO loan (idUser, duration) VALUES (:idUser, :duration)";

                            //array di valori
                            $values = array(":idUser" => $_POST['idUser'], ":duration" => $_POST['duration']);

                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //esecuzione con passaggio di valori
                                $res->execute($values);

                                //get id del prestito appena inserito
                                $idLoan = $pdo->lastInsertId();
                            } catch (PDOException $e) {
                                throw new Exception("Insert Loan Query error");
                            }


                            /* query per inserimento dati in borrow */

                            //query base da iterare per tutte le copie
                            //inserisco idLoan e idCopy
                            $query = "INSERT INTO borrow (idLoan, idCopy) VALUES (:idLoan, :idCopy)";

                            //array di valori base con id prestito
                            $values = array(":idLoan" => $idLoan);

                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //iterazione ed esecuzione per ogni copia
                                foreach ($_POST['idCopy'] as $idCopy) {
                                    //asseganzione nell'array dell'id copia sostituendo il precedente
                                    $values[":idCopy"] = $idCopy;

                                    //esecuzione con passaggio di valori
                                    $res->execute($values);
                                }
                            } catch (PDOException $e) {
                                throw new Exception("Insert Borrow Query error");
                            }
                        } catch (PDOException $e) {
                            // rollback se errore
                            $pdo->rollback();

                            throw new Exception($e);
                        }

                        //se tutti gli inserimenti sono andati a buon fine commit
                        $pdo->commit();

                        //stampo messaggio di successo
                        echo '<br><div class="alert alert-success">
                                <strong>Prestito effettuato con successo!</strong> Verrai reindirizzato al riepilogo.' .
                            '</div>';

                        header("Refresh:2; URL=admin_edit_loan.php?idLoan=" . $idLoan);
                        die();
                    } catch (Exception $e) {
                        echo '<br><div class="alert alert-danger">
                                <strong>Errore!</strong> ' . $e->getMessage() .
                            '</div>';
                    }

                    //se non sono inseriti tutti i dati restituisco errore
                } else {
                    echo '<br><div class="alert alert-danger">
                        <strong>Errore!</strong> Non hai inserito tutti i campi obbligatori.
                    </div>';
                }
            }
        ?>

            <div class="container">

                <h1>Nuovo Prestito</h1>

                <hr>

                <?php


                //get lista utenti abilitati per select utente
                $query = "SELECT idUser, name, surname
                    FROM user
                    WHERE isEnabled";

                try {
                    //preparo query
                    $res = $pdo->prepare($query);

                    //eseguo query
                    $res->execute();
                } catch (PDOException $e) {
                    //in caso di eccezione ritorno l'eccezione
                    throw new Exception('Database query error');
                }

                $users = $res->fetchAll();
                ?>

                <form action="" method="POST">
                    <div class="form-group row">
                        <label for="duration" class="col-4 col-form-label">Utente</label>
                        <div class="col-8">
                            <!-- stampa lista utenti in select -->
                            <select class="form-select-lg" name="idUser">
                                <!-- <option disabled selected>Seleziona un utente</option> -->

                                <?php
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['idUser'] . "'>" . $user['idUser'] . " - " . $user['surname'] . " " . $user['name'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Durata (giorni)</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input id="duration" name="duration" type="number" min="1" step="1" class="form-control" value="30" required>
                            </div>
                        </div>
                    </div>

                    <!-- Copie -->
                    <div id="copie">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label">Id Copia</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input id="idCopy" name="idCopy[]" type="number" min="1" step="1" class="form-control" required>
                                    <!-- primo elemento non eliminabile -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottone Aggiunta copia -->
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label"></label>
                        <div class="col-8">
                            <div class="input-group">
                                <button type="button" class="btn btn-default" onclick="addCopy()">
                                    <i class="fa fa-plus"></i> Aggiungi copia
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button name="newLoan" type="submit" class="btn btn-primary">Inserisci</button>
                            <a href="" class="btn btn-default" role="button">Reset</a>

                        </div>
                    </div>

                </form>
            </div>
        <?php


        } else {
            echo '<br><div class="alert alert-danger">
                    <strong>Errore!</strong> Non disponi dell\'autorizzazione effettuare prestiti.
                </div>';
        }
        ?>


    </div>

</body>

</html>