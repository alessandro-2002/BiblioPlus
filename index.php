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
                <input type="text" placeholder="ISBN..." name="ISBN" />
                <button type="submit">Submit</button>
            </form>

            <form action="index.php" method="get">
                <input type="text" placeholder="Titolo..." name="title" />
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

        //controllo get in caso di ricerca

        //ricerca per isbn
        if (isset($_GET['ISBN']) && $_GET['ISBN'] != "") {

            //controllo se l'isbn contiene solo numeri 
            if (ctype_digit($_GET['ISBN'])) {

                //preparo aggiunta condizione
                $query = $query . " AND ISBN = :ISBN";

                //array di valori da passare
                $values = array(':ISBN' => $_GET['ISBN']);
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
            $values = array(':title' => $_GET['title']);
        }

        //ricerca per editore
        if (isset($_GET['publisher']) && $_GET['publisher'] != "") {

            //preparo aggiunta condizione
            $query = $query . ' AND publisher.idPublisher= :publisher';

            //array di valori da passare
            $values = array(':publisher' => $_GET['publisher']);
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
                            echo $e;

                            //in caso di errore stampo con stile
                            echo "<div class=\"alert alert-danger\">
                                <strong>Errore!</strong> Errore nella ricerca
                                </div>";
                            die();
                        }

                        /* stampa tabellare */

                        echo "<tr>";

                        //cover 
                        echo "<td><img src='images/";
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
                            echo $autore['name'] . " " . $autore['surname'];

                            if (count($autori) > $index + 1) {
                                echo ", ";
                            }
                        }

                        echo "</td>";

                        //publisher
                        echo "<td><a href=\"index.php?publisher=" . $book['idPublisher'] . "\">" . $book['publisher'] . "</a></td>";

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