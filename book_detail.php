<?php

//controllo che sia passato un ISBN in get altrimenti torno su index
if (!isset($_GET['ISBN']) || $_GET['ISBN'] == "")
    header("Location: index.php");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/book_detail.css">
    <title>Biblio+</title>
</head>

<body>

    <div class="content">
        <?php
        require_once('assets/header.php');

        //cerco dati libro da mostrare
        //preparo query di ricerca
        $ISBN = htmlspecialchars($_GET['ISBN'], ENT_QUOTES);
        $query = "SELECT book.ISBN, title, subtitle, language, year, cover, name AS publisher, publisher.idPublisher, COUNT(copy.idCopy) AS copyNumber
                FROM book  
                INNER JOIN publisher ON book.idPublisher = publisher.idPublisher
                /* join con copie NON in prestito */
                LEFT JOIN copy ON book.ISBN = copy.ISBN 
                                AND copy.idCopy NOT IN (SELECT borrow.idCopy 
                                                        FROM loan, borrow
                                                        WHERE loan.idLoan = borrow.idLoan
                                                            AND returnDate IS NULL)
                /* controllo ISBN */
                WHERE book.ISBN = :ISBN
                GROUP BY book.ISBN";

        //array di valori da passare
        $values = array(':ISBN' => $ISBN);


        /* esecuzione query */
        try {
            //prepare query
            $res = $pdo->prepare($query);

            //esecuzione con passaggio di valori di ricerca
            $res->execute($values);
        } catch (PDOException $e) {

            //in caso di errore stampo con stile
            echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
            var_dump($e);
        }

        echo "<br>";

        //controllo se esiste il libro
        if ($res->rowCount() == 1) {

            //fetch
            $book = $res->fetch();

            /* ricerca degli autori */

            //query per trovare autori
            $query = "SELECT author.idAuthor, name, surname
            FROM author, write_book AS wb
            WHERE author.idAuthor = wb.idAuthor
                AND wb.ISBN = :ISBN
            ORDER BY wb.position;";



            //array di valori da passare per la query autori
            $values = array(':ISBN' => $ISBN);

            /* esecuzione query */
            try {
                //prepare query
                $res = $pdo->prepare($query);

                //esecuzione con passaggio di valori di ricerca
                $res->execute($values);

                //fetch
                $autori = $res->fetchAll();
            } catch (PDOException $e) {

                //in caso di errore stampo con stile
                echo "<div class=\"alert alert-danger\">
                        <strong>Errore!</strong> Errore nella ricerca
                        </div>";
                die();
            }

        ?>
            <!-- stampa dettaglio -->
            <div class="book">
                <!-- cover  -->
                <div class="left">
                    <?php
                    echo '<img id="cover" src="images/';
                    if ($book['cover'] != NULL) {
                        echo $book['cover'];
                    } else {
                        echo "no-image.jpg";
                    }
                    echo '"/>';
                    ?>
                </div>

                <!-- dati testutali -->
                <div class="right">
                    <table id="details">
                        <tr>
                            <td class="property">
                                ISBN:
                            </td>
                            <td class="content">
                                <?php
                                echo $ISBN;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Titolo:
                            </td>
                            <td class="content">
                                <?php
                                echo htmlentities($book['title']);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Sottotitolo:
                            </td>
                            <td class="content">
                                <?php
                                echo htmlentities($book['subtitle']);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Autori:
                            </td>
                            <td class="content">
                                <?php
                                //stampo gli autori utilizzando l'index per capire se devo mettere la virgola (o se è l'ultimo)
                                //aggiungo collegamento per ricerca nell'index
                                foreach ($autori as $index => $autore) {
                                    echo "<a href='index.php?authorId=" . $autore['idAuthor'] . "'>" . $autore['name'] . " " . $autore['surname'] . '</a>';

                                    if (count($autori) > $index + 1) {
                                        echo ", ";
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Lingua:
                            </td>
                            <td class="content">
                                <?php
                                echo htmlentities($book['language']);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Anno:
                            </td>
                            <td class="content">
                                <?php
                                echo htmlentities($book['year']);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Editore:
                            </td>
                            <td class="content">
                                <?php
                                echo "<a href='index.php?publisherId=" . $book['idPublisher'] . "'>" . htmlentities($book['publisher']) . "</a>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="property">
                                Copie disponibili:
                            </td>
                            <td class="content">
                                <?php
                                echo htmlentities($book['copyNumber']);
                                ?>
                            </td>
                        </tr>
                    </table>

                </div>
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