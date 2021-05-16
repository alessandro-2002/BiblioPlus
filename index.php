<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/home.css">

    <title>Biblio+</title>

</head>

<body>

    <div class="content">
        <?php
        require_once('assets/header.php');
        ?>

        <!-- barra di ricerca -->
        <div class="search">
            <form action="index.php" method="get">
                <input type="text" placeholder="ISBN..." name="ISBN" <?php
                                                                        //controllo se si sta cercando per isbn e lo metto nella textbox
                                                                        if (isset($_GET['ISBN']) && $_GET['ISBN'] != "") {
                                                                            echo "value='" . htmlentities($_GET['ISBN']) . "'";
                                                                        }
                                                                        ?> />
                <button type="submit">Submit</button>
            </form>

            <form action="index.php" method="get">
                <input type="text" placeholder="Titolo..." name="title" <?php

                                                                        //controllo se si sta cercando per titolo e lo metto nella textbox
                                                                        if (isset($_GET['title']) && $_GET['title'] != "") {
                                                                            echo "value='" . htmlentities($_GET['title']) . "'";
                                                                        }
                                                                        ?> />

                <br>

                <select name=publisherId>
                    <option value="" selected>Editore...</option>

                    <?php
                    /* ricerca lista editori */

                    //query 
                    $queryP = "SELECT idPublisher, name
                            FROM publisher";

                    /* esecuzione query */
                    try {
                        //prepare query
                        $res = $pdo->prepare($queryP);

                        //secuzione 
                        $res->execute();
                    } catch (PDOException $e) {

                        //in caso di errore stampo con stile
                        echo "<div class=\"alert alert-danger\">
                            <strong>Errore!</strong> Errore nella ricerca
                            </div>";
                    }

                    //fetch
                    $publishers = $res->fetchAll();

                    //stampa options
                    foreach ($publishers as $publisher) {
                        echo '<option value="' . $publisher['idPublisher'] . '"';

                        //se è in atto ricerca per editore lo seleziono
                        if (isset($_GET['publisherId']) && $_GET['publisherId'] == $publisher['idPublisher']) {
                            echo " selected ";
                        }
                        echo '>' . $publisher['name'] . '</option>';
                    }

                    ?>

                </select>

                <br>

                <select name=authorId>
                    <option value="" selected>Autore...</option>

                    <?php
                    /* ricerca lista autori */

                    //query 
                    $queryA = "SELECT idAuthor, name, surname
                            FROM author
                            ORDER BY surname";

                    /* esecuzione query */
                    try {
                        //prepare query
                        $res = $pdo->prepare($queryA);

                        //secuzione 
                        $res->execute();
                    } catch (PDOException $e) {

                        //in caso di errore stampo con stile
                        echo "<div class=\"alert alert-danger\">
                            <strong>Errore!</strong> Errore nella ricerca
                            </div>";
                    }

                    //fetch
                    $authors = $res->fetchAll();

                    //stampa options
                    foreach ($authors as $author) {
                        echo '<option value="' . $author['idAuthor'] . '"';

                        //se è in atto ricerca per autore lo seleziono
                        if (isset($_GET['authorId']) && $_GET['authorId'] == $author['idAuthor']) {
                            echo " selected ";
                        }
                        echo '>'  . $author['surname'] . ' ' . $author['name'] .  '</option>';
                    }

                    ?>

                </select>
                <button type="submit">Submit</button>
            </form>

        </div>

        <br>

        <?php

        //get books list
        //preparo query di base
        $query = "SELECT ISBN, title, subtitle, cover
                FROM book, publisher
                WHERE book.idPublisher = publisher.idPublisher";


        //array per eventuale passaggio di valori di ricerca
        $values = array();

        //controllo get in caso di ricerca

        //ricerca per isbn
        if (isset($_GET['ISBN']) && $_GET['ISBN'] != "") {

            //controllo se l'isbn contiene solo numeri 
            if (ctype_digit($_GET['ISBN'])) {

                //preparo aggiunta condizione
                $query = $query . " AND ISBN = :ISBN";

                //array di valori da passare
                $values[':ISBN'] = $_GET['ISBN'];
            } else {
                //se inserito errato stampo warning ma mostro comunque tutta la lista dei titoli
                echo "<div class=\"alert alert-warning\">
                    <strong>Attenzione!</strong> L'ISBN inserito non è valido, ricorda che è costituito da soli numeri.
                    </div>";
            }
        }

        //ricerca per titolo
        if (isset($_GET['title']) && $_GET['title'] != "") {
            //preparo aggiunta condizione
            $query = $query . ' AND title = :title';

            //array di valori da passare
            $values[':title'] = $_GET['title'];
        }

        //ricerca per editore con Id
        if (isset($_GET['publisherId']) && $_GET['publisherId'] != "") {

            //preparo aggiunta condizione
            $query = $query . ' AND publisher.idPublisher= :publisher';

            //array di valori da passare
            $values[':publisher'] = $_GET['publisherId'];
        }

        //ricerca per autore con Id
        if (isset($_GET['authorId']) && $_GET['authorId'] != "") {

            //preparo aggiunta condizione
            $query = $query . ' AND book.ISBN IN (SELECT ISBN
                                                FROM write_book
                                                WHERE idAuthor = :author)';

            //array di valori da passare
            $values[':author'] = $_GET['authorId'];
        }


        /* esecuzione query */
        try {
            //prepare query
            $res = $pdo->prepare($query);

            //secuzione con passaggio di eventuali valori di ricerca, controllo esistano
            if (isset($values)) {
                $res->execute($values);
            } else {
                $res->execute();
            }
        } catch (PDOException $e) {
            //in caso di errore stampo con stile
            echo $e->getMessage();
            echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
        }



        //controllo se ci sono libri
        if ($res->rowCount() > 0) {

            //fetch
            $books = $res->fetchAll();
        ?>

            <section style="max-width: 85%; margin:auto;">

                <!-- Grid row -->
                <div class="row">
                    <?php
                    foreach ($books as $book) {
                    ?>

                        <!-- Grid column -->
                        <div class="col-md-4 mb-4">
                            <!-- Card -->
                            <div class="">
                                <div class="view zoom overlay z-depth-2 rounded text-center">
                                    <a href="book_detail.php?ISBN=<?php echo $book['ISBN']; ?>">
                                        <img class="img-fluid rounded" src="images/<?php
                                                                                    if ($book['cover'] != NULL) {
                                                                                        echo $book['cover'];
                                                                                    } else {
                                                                                        echo "no-image.jpg";
                                                                                    }
                                                                                    ?>" style="max-width: 80%; ">
                                    </a>
                                </div>
                                <div class="text-center pt-4">
                                    <h5><?php echo htmlentities($book['title']); ?></h5>
                                    <p class="mb-2 text-muted small"><?php echo htmlentities($book['subtitle']); ?></p>
                                </div>
                            </div>
                            <!-- Card -->
                        </div>
                        <!-- Column -->

                    <?php
                    }
                    ?>
                </div>
            </section>

        <?php
        } else {

            //in caso non ci siano titoli stampo un info
            echo "<div class=\"alert alert-info\">
                <strong>Attenzione!</strong> Nessun titolo trovato.
                </div>";
        }
        ?>
    </div>


</body>

</html>