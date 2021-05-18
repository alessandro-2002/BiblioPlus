<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/toggleSwitch.css">

    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");

        //controllo ACL e se non le ha non lascio l'accesso alla pagina, stampo errore
        if ($adminAccount->getACLadmin()) {

            //controllo se in post sta inserendo prestito
            if (isset($_POST['newAdmin'])) {
                //controllo siano stati inseriti i dati
                if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['mail']) && isset($_POST['password'])) {

                    try {
                        //inserimento 
                        $ACL = array();

                        if (isset($_POST['ACLcatalogue'])) {
                            $ACL['catalogue'] = true;
                        }

                        if (isset($_POST['ACLloan'])) {
                            $ACL['loan'] = true;
                        }

                        if (isset($_POST['ACLuser'])) {
                            $ACL['user'] = true;
                        }

                        if (isset($_POST['ACLadmin'])) {
                            $ACL['admin'] = true;
                        }

                        $idRegistrato = $adminAccount->addAccount($_POST['mail'], $_POST['password'], $_POST['name'], $_POST['surname'], $ACL);

                        echo '<br><div class="alert alert-success">
                                <strong>Registrazione effettuata!</strong> Bibliotecario registrato con successo con id ' . $idRegistrato . ', verrai reindirizzato al riepilogo.
                            </div>';

                        header('Refresh: 2, URL=admin_edit_admin.php?idAdmin=' . $idRegistrato);

                        die();
                    } catch (Exception $e) {
                        echo '<br><div class="alert alert-danger">
                                <strong>Errore!</strong> ' . $e->getMessage() .
                            '</div>';
                    }

                    //se non sono inseriti tutti i dati restituisco errore
                } else {
                    echo '<br><div class="alert alert-danger">
                        <strong>Errore!</strong> Non hai inserito tutti i campi obbligatori.
                    </div>';
                }
            }
        ?>

            <div class="container">

                <h1>Nuovo Bibliotecario</h1>

                <hr>

                <form action="" method="POST">
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Nome</label>
                        <div class="col-8">
                            <input id="name" name="name" type="text" type="text" maxlength="45" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Cognome</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input id="surname" name="surname" type="text" maxlength="45" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">E-Mail</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input id="mail" name="mail" type="email" maxlength="255" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label">Password (da modificare al primo accesso)</label>
                        <div class="col-8">
                            <div class="input-group">
                                <input name="password" type="text" minlength="6" maxlength="15" class="form-control" value="Biblio2021" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL catalogo</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLcatalogue">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL prestiti</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLloan">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL Utenti</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLuser">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label">ACL Bibliotecari</label>
                        <div class="col-8">
                            <label class="switch">
                                <input type="checkbox" name="ACLadmin">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>


                    <!-- bottoni -->
                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button name="newAdmin" type="submit" class="btn btn-primary">Registra</button>
                            <button type="reset" class="btn btn-default">Reset</button>

                        </div>
                    </div>

                </form>
            </div>
        <?php


        } else {
            echo '<br><div class="alert alert-danger">
                    <strong>Errore!</strong> Non disponi dell\'autorizzazione per gestire i Bibliotecari.
                </div>';
        }
        ?>


    </div>

</body>

</html>