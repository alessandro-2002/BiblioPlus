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
                if (confirm("Sei sicuro? L'azione è irreversibile e comporta l'eliminazione di tutto lo storico prestiti!")) {
                    window.location = "admin_edit_book.php?ISBN=<?php echo $_GET['ISBN']; ?>&action=deleteBook";
                }
            }
        }

        //conferma cancellazione copia
        function confirmDeleteCopy(idCopy) {
            if (confirm("Vuoi eliminare DEFINITIVAMENTE la copia del libro dal catalogo?")) {
                if (confirm("Sei sicuro? L'azione è irreversibile e comporta l'eliminazione di tutto lo storico prestiti!")) {
                    window.location = "admin_edit_book.php?ISBN=<?php echo $_GET['ISBN']; ?>&idCopy=" + idCopy + "&action=deleteCopy";
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

        /* ricerca ISBN e dati libro */

        //query di per trovare libro
        $query = "SELECT book.ISBN AS ISBN, title, subtitle, language, year, publisher.name AS editore, cover
            FROM book, publisher
            WHERE book.idPublisher = publisher.idPublisher
                AND book.ISBN = :ISBN";


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

            //salvo libro
            $book = $res->fetchAll()[0];

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
                                    <strong>Libro eliminato con successo!</strong> Verrai reindirizzato al catalogo.' .
                                '</div>';

                            //eliminazione immagine
                            if($book['cover']!=NULL){
                                if(file_exists("../images/" . $book['cover'])){
                                    unlink("../images/" . $book['cover']);
                                }
                            }

                            //redirect a pagina libri
                            header("Refresh:2; URL=admin_books.php");
                            die();
                        } catch (PDOException $e) {

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                    <strong>Errore!</strong> Errore nell'eliminazione.
                                </div>";
                        }

                        //controllo se sta eliminando copia
                    } else if (isset($_GET['idCopy']) && $_GET['action'] == 'deleteCopy') {

                        /* eliminazione copia */

                        //query di per trovare copia
                        $query = "DELETE FROM copy
                                WHERE idCopy = :idCopy";


                        //array di valori da passare
                        $values = array(':idCopy' => $_GET['idCopy']);

                        /* esecuzione query */

                        try {

                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione con passaggio di valori 
                            $res->execute($values);

                            //messaggio di conferma
                            echo '<br><div class="alert alert-success">
                                    <strong>Copia eliminata con successo!</strong> La pagina verr&agrave; ricaricata.' .
                                '</div>';

                            //redirect a pagina libri
                            header("Refresh:2; URL=admin_edit_book.php?ISBN=" . $_GET['ISBN']);
                            die();
                        } catch (PDOException $e) {

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                    <strong>Errore!</strong> Errore nell'eliminazione.
                                </div>";
                        }

                        //controllo se sta aggiungendo una nuova copia
                    } else if ($_GET['action'] == 'newCopy') {

                        /* aggiunta copia */

                        //query di insert copia
                        $query = "INSERT INTO copy(ISBN)
                               VALUES(:ISBN)";


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
                                    <strong>Copia aggiunta con successo!</strong> La pagina verr&agrave; ricaricata.' .
                                '</div>';

                            //redirect a pagina libri
                            header("Refresh:2; URL=admin_edit_book.php?ISBN=" . $_GET['ISBN']);
                            die();
                        } catch (PDOException $e) {

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                    <strong>Errore!</strong> Errore nell'aggiunta.
                                </div>" . $e->getMessage();
                        }
                    }
                }
            }

        ?>

            <!-- VISTA LIBRO -->
            <div class="container">
                <h1>Modifica Libro</h1>
                <hr>
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-3">
                        <div class="text-center">
                            <img src="../images/<?php
                                                if ($book['cover'] != NULL) {
                                                    echo $book['cover'];
                                                } else {
                                                    echo "no-image.jpg";
                                                }
                                                ?>" class="cover img-circle" alt="cover">
                        </div>
                    </div>

                    <!-- edit form column -->
                    <div class="col-md-9 personal-info">

                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">ISBN:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['ISBN']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Titolo:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['title']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Sottotitolo:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['subtitle']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Lingua:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['language']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Anno di pubblicazione:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['year']; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Editore:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php echo $book['editore']; ?>
                                    </p>
                                </div>
                            </div>

                            <?php
                            //query per trovare autori
                            $query = "SELECT author.name 
                                    FROM author, write_book
                                    WHERE author.idAuthor = write_book.idAuthor
                                        AND write_book.ISBN = :ISBN";


                            //array di valori da passare
                            $values = array(':ISBN' => $_GET['ISBN']);

                            /* esecuzione query */

                            try {

                                //prepare query
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
                            ?>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Autori:</label>
                                <div class="col-lg-8">
                                    <p class="form-control">
                                        <?php
                                        if ($res->rowCount() >= 1) {
                                            $authors = $res->fetchAll(PDO::FETCH_COLUMN, 0);
                                            echo implode(", ", $authors);
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <?php
                            //se ha autorizzazione stampo form per eliminazione libro
                            if ($adminAccount->getACLcatalogue()) {
                            ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-8">
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

            <!-- COPIE -->

            <h1>Copie</h1>

            <?php
            //controllo se ha acl per aggiunta copie altrimenti non stampo bottone di aggiunta
            if ($adminAccount->getACLcatalogue()) {
            ?>

                <!-- button per aggiungere libro -->
                <a href="admin_edit_book.php?ISBN=<?php echo $_GET['ISBN']; ?>&action=newCopy">
                    <button type=" button" class="btn btn-lg" aria-label=" Left Align">
                        <i class="fa fa-plus"></i> Aggiungi copia
                    </button>
                </a>

                <br><br>
            <?php
            }
            ?>

            <?php
            //get copie (idCopia, idPrestito (Se NULL vuol dire che è libera)) 
            $query = "SELECT copy.idCopy, borrow.idLoan AS stato
                    FROM copy
                        LEFT OUTER JOIN borrow ON copy.idCopy = borrow.idCopy AND borrow.idLoan IN (SELECT idLoan
                                                                                                    FROM loan
                                                                                                    WHERE returnDate IS NULL)
                    WHERE copy.ISBN = :ISBN";

            $values = array(':ISBN' => $_GET['ISBN']);

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
                    <strong>Errore!</strong> Errore nella ricerca delle copie.
                </div>";
                die();
            }

            //controllo se ci sono copie
            if ($res->rowCount() > 0) {

                //fetch
                $copies = $res->fetchAll();

            ?>

                <!-- Visualizzazione tabellare delle copie -->
                <div class="loans">

                    <table>

                        <!-- intestazione -->
                        <tr>

                            <?php
                            //controllo se ha acl di modifica per poter eliminare la copia o non stampo il bottone
                            if ($adminAccount->getACLcatalogue()) {
                            ?>
                                <th>
                                    <!-- Colonna per azioni sulle copie -->
                                </th>
                            <?php
                            }
                            ?>

                            <th>
                                Id Copia
                            </th>
                            <th>
                                Stato
                            </th>
                        </tr>

                        <?php

                        foreach ($copies as $copy) {
                            echo "<tr>";

                            //elimina copia se ha acl
                            if ($adminAccount->getACLcatalogue()) {
                                echo "<td style='max-width:25px'>
                                    <button type=\"button\" class=\"btn btn-default\" onclick='confirmDeleteCopy(" . $copy['idCopy'] . ")'>
                                        <i class='fa fa-trash'></i>
                                    </button>
                                </td>";
                            }

                            //id
                            echo "<td>" . $copy['idCopy'] . "</td>";

                            //stato
                            echo "<td";

                            //controllo se prestito
                            if ($copy['stato'] == NULL) {
                                echo ">Non in prestito";
                            } else {
                                echo " class='inLoan'><a href='admin_edit_loan.php?idLoan=" . $copy['stato'] . "'>Prestito " . $copy['stato'] . "</a>";
                            }

                            echo "</td>";

                            echo "</tr>";
                        }
                        ?>

                    </table>
                </div>

        <?php
            }
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