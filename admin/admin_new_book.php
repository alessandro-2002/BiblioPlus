<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">

    <script src="../js/admin_new_book.js"></script>

    <title>Area Bibliotecario</title>

</head>

<body>

    <div class="content">
        <?php

        //importo header
        require_once('../assets/admin_header.php');

        //controllo ACL e se non le ha non lascio l'accesso alla pagina, stampo errore
        if ($adminAccount->getACLcatalogue()) {

            //in caso ci sia inserimento in post aggiungo il titolo con transazione
            if (isset($_POST['ISBN']) && isset($_POST['title']) && isset($_POST['publisher'])) {

                //blocco try-catch unico
                try {

                    /* controllo esistenza ISBN */

                    //query di ricerca per libro esistente
                    $query = "SELECT ISBN
                                FROM book
                                WHERE ISBN=:ISBN";

                    //array di valori da passare
                    $values = array(':ISBN' => $_POST['ISBN']);

                    // esecuzione query 
                    try {
                        //prepare query
                        $res = $pdo->prepare($query);

                        //esecuzione con passaggio di valori
                        $res->execute($values);
                    } catch (PDOException $e) {
                        throw new Exception("Query error");
                    }

                    //controllo se non c'è libro, in tal caso lancio eccezione altrimenti continuo
                    if ($res->rowCount() != 0) {
                        throw new Exception("ISBN gi&agrave; presente nel catalogo.");
                    }

                    //inizio transazione per inserimenti
                    try {
                        $pdo->beginTransaction();

                        /* controllo match con editore, in tal caso salvo id */

                        //query di ricerca per editore esistente
                        $query = "SELECT idPublisher
                                FROM publisher
                                WHERE name=:name";

                        //array di valori da passare
                        $values = array(':name' => $_POST['publisher']);

                        // esecuzione query 
                        try {
                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione con passaggio di valori
                            $res->execute($values);
                        } catch (PDOException $e) {
                            throw new Exception("Query error");
                        }

                        //controllo se c'è editore, in tal caso salvo id altrimenti lo inserisco
                        if ($res->rowCount() == 1) {
                            $idEditore = $res->fetchAll();
                            $idEditore = $idEditore[0]['idPublisher'];
                        } else {
                            //query di inserimento per editore
                            $query = "INSERT INTO publisher(name) VALUES(:name)";

                            //array di valori da passare
                            $values = array(':name' => $_POST['publisher']);

                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //esecuzione con passaggio di valori
                                $res->execute($values);

                                //salvo l'id dell'editore appena inserito
                                $idEditore = $pdo->lastInsertId();
                            } catch (PDOException $e) {
                                throw new Exception("Query error");
                            }
                        }

                        /* controllo match con autori e generazione array con id altrimenti li inserisco */
                        $autori = array();

                        //query base ricerca per autore esistente
                        $query = "SELECT idAuthor
                                FROM author
                                WHERE name=:name";

                        //foreach per scorrere array autori in post
                        foreach ($_POST['authors'] as $author) {
                            //array di valori da passare
                            $values = array(':name' => $author);

                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //esecuzione con passaggio di valori
                                $res->execute($values);
                            } catch (PDOException $e) {
                                throw new Exception("Query error");
                            }

                            //controllo se c'è autore, in tal caso salvo id altrimenti lo inserisco
                            if ($res->rowCount() == 1) {
                                $idAutore = $res->fetchAll();
                                $autori[] = $idAutore[0]['idAuthor'];
                            } else {
                                //query di inserimento per autore
                                $query = "INSERT INTO author(name) VALUES(:name)";

                                //array di valori da passare
                                $values = array(':name' => $author);

                                // esecuzione query 
                                try {
                                    //prepare query
                                    $res = $pdo->prepare($query);

                                    //esecuzione con passaggio di valori
                                    $res->execute($values);

                                    //salvo l'id dell'autore appena inserito
                                    $autori[] = $pdo->lastInsertId();
                                } catch (PDOException $e) {
                                    throw new Exception("Query error");
                                }
                            }
                        }

                        /* Inserimento del nuovo libro */

                        //array di valori da passare
                        $values = array(':ISBN' => $_POST['ISBN'], ':idPublisher' => $idEditore);

                        //salvo tutti i parametri e gestisco i NULL
                        if (isset($_POST['title'])) {
                            $values[':title'] = $_POST['title'];
                        } else {
                            throw new Exception("Nessun titolo inserito.");
                        }
                        if (isset($_POST['subtitle']) && $_POST['subtitle'] != "") {
                            $values[':subtitle'] = $_POST['subtitle'];
                        } else {
                            $values[':subtitle'] = NULL;
                        }
                        if (isset($_POST['language']) && $_POST['language'] != "") {
                            $values[':language'] = $_POST['language'];
                        } else {
                            $values[':language'] = NULL;
                        }
                        if (isset($_POST['year']) && $_POST['year'] != "") {
                            $values[':year'] = $_POST['year'];
                        } else {
                            $values[':year'] = NULL;
                        }

                        //gestione cover ora ci penso
                        $values[':cover'] = NULL;

                        //query di inserimento per libro
                        $query = "INSERT INTO book(ISBN, title, subtitle, language, year, cover, idPublisher) 
                                VALUES(:ISBN, :title, :subtitle, :language, :year, :cover, :idPublisher)";

                        // esecuzione query 
                        try {
                            //prepare query
                            $res = $pdo->prepare($query);

                            //esecuzione con passaggio di valori
                            $res->execute($values);
                        } catch (PDOException $e) {
                            throw new Exception("Query error " . $e->getMessage());
                        }

                        /* Inserimento nella nn write_book */

                        //query di base inserimento autore, libro in nn
                        $query = "INSERT INTO write_book(ISBN, idAuthor, position) 
                                VALUES(:ISBN, :idAuthor, :position)";

                        //array di valori da passare
                        $values = array(':ISBN' => $_POST['ISBN']);

                        //foreach per scorrere autori
                        foreach ($autori as $index => $autore) {

                            //inserimento idAutore in values da passare
                            $values[':idAuthor'] = $autore;
                            $values[':position'] = $index;

                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //esecuzione con passaggio di valori
                                $res->execute($values);
                            } catch (PDOException $e) {
                                throw new Exception("Query error");
                            }
                        }

                        /* inserimento n copie */
                        //query di base inserimento copie
                        $query = "INSERT INTO copy(ISBN) 
                                VALUES(:ISBN)";

                        //array di valori da passare
                        $values = array(':ISBN' => $_POST['ISBN']);

                        for ($i = 0; $i < $_POST['nCopie']; $i++) {
                            // esecuzione query 
                            try {
                                //prepare query
                                $res = $pdo->prepare($query);

                                //esecuzione con passaggio di valori
                                $res->execute($values);
                            } catch (PDOException $e) {
                                throw new Exception("Query error");
                            }
                        }


                        //se tutti gli inserimenti sono andati a buon fine commit
                        $pdo->commit();

                        //stampo messaggio di successo
                        echo '<br><div class="alert alert-success">
                                <strong>Libro inserito con successo!</strong> Verrai reindirizzato al riepilogo.' .
                            '</div>';

                        header("Refresh:2; URL=admin_edit_book.php?ISBN=" . $_POST['ISBN']);
                        die();
                    } catch (Exception $e) {
                        // rollback se errore
                        $pdo->rollback();
                        throw new Exception($e);
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">
                            <strong>Errore!</strong> ' . $e->getMessage() . '
                        </div>';
                }
            }

        ?>

            <!-- VISTA LIBRO -->
            <div class="container">
                <h1>Nuovo Libro</h1>

                <hr>

                <form method="POST" action="" id="newBook">
                    <div class="form-group row">
                        <label class="col-4 col-form-label">ISBN:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="text" name="ISBN" id="ISBN" required>
                                <button type="button" class="btn btn-default btn-xs remove" onclick="searchISBN()">
                                    Cerca con ISBN
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Copertina:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input type="file" name="coverFile" class="form-control">
                                <input class="form-control" type="url" name="coverLink" id="coverLink" placeholder="Oppure inserisci un link...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Titolo:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="text" maxlength="45" name="title" id="title" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Sottotitolo:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="text" maxlength="45" name="subtitle" id="subtitle">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Lingua:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="text" maxlength="20" name="language" id="language">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Anno di pubblicazione:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="number" name="year" id="year" step="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Editore:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="text" name="publisher" id="publisher" required>
                            </div>
                        </div>
                    </div>

                    <!-- Autori -->
                    <div id="autori">
                    </div>

                    <!-- Bottone Aggiunta Autore -->
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label"></label>
                        <div class="col-8">
                            <div class="input-group">
                                <button type="button" class="btn btn-default" onclick="addAuthor('')">
                                    <i class="fa fa-plus"></i> Aggiungi autore
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">Numero copie:</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input class="form-control" type="number" name="nCopie" id="nCopie" step="1" min="0" value=0 required>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <input type="submit" class="btn btn-primary" value="Inserisci">
                            <span></span>
                            <input type="reset" class="btn btn-default" value="Reset">

                        </div>
                    </div>

                </form>
            </div>


        <?php

        } else {
            echo '<br><div class="alert alert-danger">
                    <strong>Errore!</strong> Non disponi dell\'autorizzazione per aggiungere titoli.
                </div>';
        }
        ?>
    </div>


    <br><br>

</body>

</html>