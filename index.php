<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Biblioteca facile!</title>

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
                                                                            echo "value='" . htmlspecialchars($_GET['ISBN'], ENT_QUOTES) . "'";
                                                                        }
                                                                        ?> />
                <button type="submit">Submit</button>
            </form>

            <form action="index.php" method="get">
                <input type="text" placeholder="Titolo..." name="title" <?php

                                                                        //controllo se si sta cercando per titolo e lo metto nella textbox
                                                                        if (isset($_GET['title']) && $_GET['title'] != "") {
                                                                            echo "value='" . htmlspecialchars($_GET['title'], ENT_QUOTES) . "'";
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
        $query = "SELECT ISBN, title, subtitle, cover, name AS publisher, publisher.idPublisher AS idPublisher
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
            echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
        }



        //controllo se ci sono libri
        if ($res->rowCount() > 0) {

            //fetch
            $books = $res->fetchAll();
        ?>

            <!-- Visualizzazione tabellare dei libri -->
            <div class="books">

                <table>

                    <!-- intestazione -->
                    <tr>
                        <th>
                            Immagine
                        </th>
                        <th>
                            ISBN
                        </th>
                        <th>
                            Titolo
                        </th>
                        <th>
                            Sottotitolo
                        </th>
                        <th>
                            Autori
                        </th>
                        <th>
                            Editore
                        </th>
                    </tr>

                    <?php

                    //query per trovare autori
                    $query = "SELECT author.idAuthor, name, surname
                    FROM author, write_book AS wb
                    WHERE author.idAuthor = wb.idAuthor
                        AND wb.ISBN = :ISBN
                    ORDER BY wb.position;";

                    //prepare query, ottimizza l'esecuzione
                    $res = $pdo->prepare($query);


                    //stampa dei libri in tabella
                    foreach ($books as $book) {

                        /* ricerca degli autori */

                        //array di valori da passare per la query autori
                        $values = array(':ISBN' => $book['ISBN']);

                        /* esecuzione query */
                        try {
                            //esecuzione con passaggio di eventuali valori di ricerca, controllo esistano
                            if (isset($values)) {
                                $res->execute($values);
                            }

                            //fetch
                            $autori = $res->fetchAll();
                        } catch (PDOException $e) {

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                <strong>Errore!</strong> Errore nella ricerca
                                </div>";
                            die();
                        }

                        /* stampa tabellare */

                        echo "<tr>";

                        //cover 
                        echo "<td class='picture-box'><img src='images/";
                        if ($book['cover'] != NULL) {
                            echo $book['cover'];
                        } else {
                            echo "no-image.jpg";
                        }
                        echo "' />";

                        //ISBN
                        echo "<td>" . $book['ISBN'] . "</td>";

                        //title
                        echo "<td><a href=\"book_detail.php?ISBN=" . $book['ISBN'] . "\">" . $book['title'] . "</a></td>";

                        //subtitle
                        echo "<td>" . $book['subtitle'] . "</td>";

                        //autori
                        echo "<td>";

                        //stampo gli autori utilizzando l'index per capire se devo mettere la virgola (o se è l'ultimo)
                        foreach ($autori as $index => $autore) {
                            echo '<a href="index.php?authorId=' . $autore['idAuthor'] . '">' . $autore['name'] . " " . $autore['surname'] . '</a>';

                            if (count($autori) > $index + 1) {
                                echo ", ";
                            }
                        }

                        echo "</td>";

                        //publisher
                        echo "<td><a href=\"index.php?publisherId=" . $book['idPublisher'] . "\">" . $book['publisher'] . "</a></td>";

                        echo "</tr>";
                    }
                    ?>


                </table>

            </div>

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