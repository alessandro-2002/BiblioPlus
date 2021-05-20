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

            /* DA FARE ANCORA */
            /*
                if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['mail']) && $adminAccount->getACLuser()) {

                    //invoco la funzione per l'edit dell'utente
                    try {
                        //creo array per parametri opzionali indirizzo e enabled
                        $options = array('address' => $_POST['address']);

                        if (isset($_POST['enabled']) && $_POST['enabled'] == "on") {
                            $options['enabled'] = 1;
                        } else {
                            $options['enabled'] = 0;
                        }

                        $user->editAccount($user->getId(), $_POST['name'], $_POST['surname'], $_POST['mail'], $options);

                        //stampo successo e ricarico pagina
                        echo '<br><div class="alert alert-success">
                        <strong>Modifica effettuata!</strong> Modifiche apportate con successo, la pagina verr&agrave; ricaricata.
                    </div>';

                        //in caso la modifica sia andata a buon fine la pagina viene ricaricata e poi die()
                        header('Refresh: 3');
                        die();


                        //in caso di errore, si stampa l'errore e si lascia stampare il resto della pagina
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">
                        <strong>Errore!</strong> ' . $e->getMessage() . '
                    </div>';
                    }
                }*/

        ?>

            <!-- VISTA USER -->
            <div class="container">
                <h1>Nuovo Libro</h1>

                <hr>

                <form method="POST" action="">
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
                                <input class="form-control" type="year" name="year" id="publisher">
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