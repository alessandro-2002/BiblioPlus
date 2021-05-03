<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/admin_user.css">
    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");
        ?>

        <h1>Gestione Utenti</h1>

        <?php
        //get utenti 
        $query = "SELECT idUser, name, surname, mail, address, avatar, isEnabled 
                FROM user
                ORDER BY idUser ASC";


        /* esecuzione query */
        try {
            //prepare query
            $res = $pdo->prepare($query);

            //esecuzione 
            $res->execute();
        } catch (PDOException $e) {
            //in caso di errore stampo con stile
            echo $e->getMessage();
            echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
            die();
        }



        //controllo se ci sono utenti
        if ($res->rowCount() > 0) {

            //fetch
            $users = $res->fetchAll();

        ?>

            <!-- Visualizzazione tabellare degli utenti -->
            <div class="users">

                <table>

                    <!-- intestazione -->
                    <tr>
                        <th>
                            Avatar
                        </th>
                        <th>
                            Id Utente
                        </th>
                        <th>
                            Mail
                        </th>
                        <th>
                            Cognome
                        </th>
                        <th>
                            Nome
                        </th>
                        <th>
                            Indirizzo
                        </th>
                        <th>
                            Stato
                        </th>
                    </tr>

                    <?php

                    //stampa degli utenti in tabella
                    foreach ($users as $user) {
                        /* stampa tabellare */

                        echo "<tr>";

                        //avatar
                        echo "<td><a href='admin_edit_user.php?idUser=" . $user['idUser'] . "'><img src='../avatars/";
                        if ($user['avatar'] != NULL) {
                            echo $user['avatar'];
                        } else {
                            echo "no-avatar.jpg";
                        }
                        echo "' class='avatar'></a></td>";

                        //id utente
                        echo "<td>" . $user['idUser'] . "</td>";

                        //mail
                        echo "<td>" . $user['mail'] . "</td>";

                        //cognome
                        echo "<td>" . $user['surname'] . "</td>";

                        //nome
                        echo "<td>" . $user['name'] . "</td>";

                        //indirizzo
                        echo "<td>" . $user['address'] . "</td>";

                        //abilitazione
                        //abilitato o non abilitato
                        echo "<td";

                        if ($user['isEnabled']) {
                            //se abilitato
                            echo " class='enabled'>Abilitato";
                        } else {
                            //se disabilitato
                            echo " class='disabled'>Disabilitato";
                        }

                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>


                </table>

            </div>

        <?php

        } else {
            //in caso non ci sia nessun utente warning
            echo "<div class=\"alert alert-warning\">
                    <strong>Attenzione!</strong> Ancora nessun prestito inserito nel sistema.
                </div>";
        }
        ?>

    </div>

</body>

</html>