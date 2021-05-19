<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/books.css">

    <script>
        /* redirect durante ricerca per isbn */
        function searchISBN() {
            // div container delle copie
            var isbn = document.getElementById("isbn").value;

            //redirect in get
            window.location.href = "admin_books.php?ISBN=" + isbn;
        }
    </script>

    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");

        /* query per select nella ricerca */

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

        ?>

        <h1>Gestione Catalogo</h1>

        <!-- barra di ricerca -->

        <div id="search" class="text-center">
            <div class="col-md-12">
                <div class="input-group" id="adv-search">

                    <!-- ricerca per isbn -->
                    <input type="text" class="form-control" name="ISBN" id="isbn" placeholder="Cerca per ISBN" <?php
                                                                                                                //controllo se si sta cercando per isbn e lo metto nella textbox
                                                                                                                if (isset($_GET['ISBN']) && $_GET['ISBN'] != "") {
                                                                                                                    echo "value='" . htmlentities($_GET['ISBN']) . "'";
                                                                                                                }
                                                                                                                ?> />

                    <!-- ricerca per altri parametri nel dropdown -->
                    <div class="input-group-btn">
                        <div class="btn-group" role="group">
                            <div class="dropdown dropdown-lg">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>

                                <div class="dropdown-menu dropdown-menu-right" role="menu">

                                    <form class="form-horizontal" role="form">

                                        <!-- ordinamento -->
                                        <div class="form-group">
                                            <label for="orderBy">Ordina per</label>
                                            <select class="form-control" name="orderBy">
                                                <option value="ISBN" <?php
                                                                        //controllo se sta ordinando per nulla o per ISBN, in tal caso metto selected
                                                                        if (!isset($_GET['orderBy']) || $_GET['orderBy'] == "" || $_GET['orderBy'] == "ISBN") {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>ISBN</option>
                                                <option value="title" <?php
                                                                        //controllo se sta title, in tal caso metto selected
                                                                        if (isset($_GET['orderBy']) && $_GET['orderBy'] == "title") {
                                                                            echo "selected";
                                                                        }
                                                                        ?>>Titolo</option>
                                            </select>
                                        </div>

                                        <!-- ricerca per titolo -->
                                        <div class="form-group">
                                            <label for="publisherId">Titolo</label>
                                            <input type="text" class="form-control" name="title" placeholder="Titolo..." <?php
                                                                                                                            //controllo se si sta cercando per titolo e lo metto nella textbox
                                                                                                                            if (isset($_GET['title']) && $_GET['title'] != "") {
                                                                                                                                echo "value='" . htmlentities($_GET['title']) . "'";
                                                                                                                            }
                                                                                                                            ?> />
                                        </div>

                                        <!-- ricerca per editore -->
                                        <div class="form-group">
                                            <label for="publisherId">Editore</label>
                                            <select class="form-control" name=publisherId>
                                                <option value="" selected>Editore...</option>

                                                <?php

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
                                        </div>

                                        <!-- ricerca per autore -->
                                        <div class="form-group">
                                            <label for="authorId">Autore</label>
                                            <select class="form-control" name=authorId>
                                                <option value="" selected>Autore...</option>

                                                <?php
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

                                        </div>

                                        <!-- bottone per lente di ingrandimento ricerca avanzata -->
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                        <a href="admin_books.php" class="btn btn-default" role="button">Reset</a>
                                    </form>

                                </div>
                            </div>

                            <!-- bottone per lente di ingrandimento ricerca isbn -->
                            <button onclick="searchISBN()" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
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

        //tipo di ordinamento 
        //ultimo controllo in quanto l'order by va posto alla fine della query
        if (isset($_GET['orderBy']) && $_GET['orderBy'] != "") {
            //preparo aggiunta condizione
            if ($_GET['orderBy'] == "ISBN") {
                $query = $query . ' ORDER BY book.ISBN';
            } else if ($_GET['orderBy'] == "title") {
                $query = $query . ' ORDER BY book.title';
            }
            //se non è specificato di default è ISBN
        } else {
            $query = $query . ' ORDER BY book.ISBN';
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

        //controllo se ha acl per aggiunta libro altrimenti non stampo bottone
        if ($adminAccount->getACLcatalogue()) {
        ?>

            <!-- button per aggiungere libro -->
            <a href="admin_new_book.php">
                <button type="button" class="btn btn-lg" aria-label=" Left Align">
                    <i class="fa fa-plus"></i> Nuovo Titolo
                </button>
            </a>

            <br><br>
        <?php
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
                            <!-- colonna azioni -->
                        </th>
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

                        //azioni
                        echo "<td><a href='admin_book_detail.php?ISBN=" . $book['ISBN'] . "'>";

                        echo "<button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">
                            <i class=\"fas fa-edit\"></i>
                        </button>";

                        echo "</a></td>";

                        //cover 
                        echo "<td class='picture-box'><img src='../images/";
                        if ($book['cover'] != NULL) {
                            echo $book['cover'];
                        } else {
                            echo "no-image.jpg";
                        }
                        echo "' />";

                        //ISBN
                        echo "<td>" . $book['ISBN'] . "</td>";

                        //title
                        echo "<td>" . $book['title'] . "</td>";

                        //subtitle
                        echo "<td>" . $book['subtitle'] . "</td>";

                        //autori
                        echo "<td>";

                        //stampo gli autori utilizzando l'index per capire se devo mettere la virgola (o se è l'ultimo)
                        foreach ($autori as $index => $autore) {
                            echo '<a href="admin_books.php?authorId=' . $autore['idAuthor'] . '">' . $autore['name'] . " " . $autore['surname'] . '</a>';

                            if (count($autori) > $index + 1) {
                                echo ", ";
                            }
                        }

                        echo "</td>";

                        //publisher
                        echo "<td><a href=\"admin_books.php?publisherId=" . $book['idPublisher'] . "\">" . $book['publisher'] . "</a></td>";

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