<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/loans.css">
    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");
        ?>

        <h1>Gestione Prestiti</h1>

        <?php
        //get prestiti (idPrestito, dataInizio, dataScadenza, dataRestituzione), tutte le date sono formattate da DB
        $query = "SELECT idLoan, 
                DATE_FORMAT(loanDate, '%d-%m-%Y %H:%i') AS loanDate, 
                DATE_FORMAT(DATE_ADD(loanDatE, INTERVAL duration DAY), '%d-%m-%Y %H:%i') AS expireDate, 
                DATE_FORMAT(returnDate, '%d-%m-%Y %H:%i') AS returnDate,
                user.idUser, user.name, user.surname
            FROM loan, user
            WHERE loan.idUser = user.idUser
            ORDER BY loanDate DESC";

        /* esecuzione query */
        try {
            //prepare query
            $res = $pdo->prepare($query);

            //esecuzione
            $res->execute();
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


                <?php
                //controllo se ha acl per aggiunta prestito altrimenti non stampo bottone
                if ($adminAccount->getACLloan()) {
                ?>

                    <!-- button per aggiungere prestito -->
                    <a href="admin_new_loan.php">
                        <button type="button" class="btn btn-lg" aria-label=" Left Align">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuovo Prestito
                        </button>
                    </a>

                    <br><br>
                <?php
                }
                ?>

                <table>

                    <!-- intestazione -->
                    <tr>
                        <th>
                            <!-- colonna azioni -->
                        </th>
                        <th>
                            Id
                        </th>
                        <th>
                            Utente
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
                            Stato
                        </th>
                    </tr>

                    <?php


                    /* stampa tabellare */
                    foreach ($loans as $loan) {

                        echo "<tr>";

                        //azioni
                        echo "<td><a href='admin_edit_loan.php?idLoan=" . $loan['idLoan'] . "' target='_blank'>";

                        echo "<button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">
                        <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                        </button>";

                        echo "</a></td>";

                        //id prestito
                        echo "<td>" . $loan['idLoan'] . "</td>";

                        //utente
                        echo '<td><a href="admin_edit_user.php?idUser=' . $loan['idUser'] . '">' . $loan['name'] . ' ' . $loan['surname'] . '</a></td>';

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