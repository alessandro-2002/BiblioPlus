<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/loans.css">
    <title>Biblioteca facile!</title>
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


        //get prestiti (idPrestito, dataInizio, dataScadenza, dataRestituzione), tutte le date sono formattate da DB
        $query = "SELECT idLoan, 
                    DATE_FORMAT(loanDate, '%d-%m-%Y %H:%i') AS loanDate, 
                    DATE_FORMAT(DATE_ADD(loanDatE, INTERVAL duration DAY), '%d-%m-%Y %H:%i') AS expireDate, 
                    DATE_FORMAT(returnDate, '%d-%m-%Y %H:%i') AS returnDate 
                FROM loan
                WHERE idUser = :idUser
                ORDER BY loanDate DESC";

        $values = array(':idUser' => $account->getId());

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
                        echo "<td>" . $loan['idLoan'] . "</td>";

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
                            echo '<a href="index.php?ISBN=' . $titolo['ISBN'] . '">' . htmlentities($titolo['title']) . "</a> (" . $titolo['idCopy'] . ')';

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





    </div>

</body>

</html>