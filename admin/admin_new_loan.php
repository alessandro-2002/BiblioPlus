<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/loans.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">


    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");
        ?>

        <div class="container">
            <h1>Nuovo Prestito</h1>

            <hr>

            <?php
            //controllo ACL e se non le ha non lascio l'accesso alla pagina, stampo errore
            if ($adminAccount->getACLloan()) {

                //get lista utenti abilitati per select utente
                $query = "SELECT idUser, name, surname
                    FROM user
                    WHERE isEnabled";

                try {
                    //preparo query
                    $res = $pdo->prepare($query);

                    //eseguo query
                    $res->execute();
                } catch (PDOException $e) {
                    //in caso di eccezione ritorno l'eccezione
                    throw new Exception('Database query error');
                }

                $users = $res->fetchAll();
            ?>

                <form action="" method="POST">
                    <div class="form-group row">
                        <label for="duration" class="col-4 col-form-label">Utente</label>
                        <div class="col-8">
                            <!-- stampa lista utenti in select -->
                            <select class="form-select" name="idUser">
                                <option disabled selected>Seleziona un utente</option>

                                <?php
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['idUser'] . "'>" . $user['idUser'] . " - " . $user['surname'] . " " . $user['name'] . "</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Durata (giorni)</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input id="duration" name="duration" type="number" min="1" step="1" class="form-control" value="30">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button name="submit" type="submit" class="btn btn-primary">Aggiorna scadenza</button>
                            <button name="submit" type="reset" class="btn">Reset</button>
                        </div>
                    </div>


                <?php


            } else {
                echo '<br><div class="alert alert-danger">
                        <strong>Errore!</strong> Non disponi dell\'autorizzazione effettuare prestiti.
                    </div>';
            }
                ?>
                </form>
        </div>
    </div>

</body>

</html>