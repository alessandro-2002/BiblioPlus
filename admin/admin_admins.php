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

        //controllo immediatamente se c'è ACL altrimenti nego l'accesso
        if ($adminAccount->getACLadmin()) {
        ?>

            <h1>Gestione Bibliotecari</h1>

            <?php
            //get utenti 
            $query = "SELECT idAdmin, name, surname, mail, ACLcatalogue, ACLloan, ACLuser, ACLadmin
                FROM admin
                ORDER BY idAdmin ASC";


            /* esecuzione query */
            try {
                //prepare query
                $res = $pdo->prepare($query);

                //esecuzione 
                $res->execute();
            } catch (PDOException $e) {
                //in caso di errore stampo con stile
                echo "<div class=\"alert alert-danger\">
                    <strong>Errore!</strong> Errore nella ricerca
                </div>";
                die();
            }



            //controllo se ci sono bibliotecari
            if ($res->rowCount() > 0) {

                //fetch
                $admins = $res->fetchAll();

            ?>

                <!-- Visualizzazione tabellare degli utenti -->
                <div class="users">

                    <table>

                        <!-- intestazione -->
                        <tr>
                            <th>
                                <!-- colonna azioni -->
                            </th>
                            <th>
                                Id
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
                                Catalogo
                            </th>
                            <th>
                                Prestiti
                            </th>
                            <th>
                                Utenti
                            </th>
                            <th>
                                Bibliotecari
                            </th>
                        </tr>

                        <?php

                        //stampa degli admin in tabella
                        foreach ($admins as $admin) {
                            /* stampa tabellare */

                            echo "<tr>";

                            //azioni
                            echo "<td><a href='admin_edit_admin.php?idAdmin=" . $admin['idAdmin'] . "'>";

                            echo "<button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">
                                    <i class=\"fas fa-edit\"></i>
                                </button>";

                            echo "</a></td>";


                            //id admin
                            echo "<td><a href='admin_edit_admin.php?idAdmin=" . $admin['idAdmin'] . "'>" . $admin['idAdmin'] . "</a>";

                            //mail
                            echo "<td>" . $admin['mail'] . "</td>";

                            //cognome
                            echo "<td>" . $admin['surname'] . "</td>";

                            //nome
                            echo "<td>" . $admin['name'] . "</td>";

                            //acl catalogo
                            echo "<td>";

                            //se consentito
                            if ($admin['ACLcatalogue']) {
                                echo '<span class="badge badge-success">Consentito</span>';
                                //se non consentito 
                            } else {
                                echo '<span class="badge badge-danger">NON consentito</span>';
                            }
                            echo "</td>";

                            //acl prestiti
                            echo "<td>";
                            //se consentito
                            if ($admin['ACLloan']) {
                                echo '<span class="badge badge-success">Consentito</span>';
                                //se non consentito 
                            } else {
                                echo '<span class="badge badge-danger">NON consentito</span>';
                            }
                            echo "</td>";

                            //acl utenti
                            echo "<td>";
                            //se consentito
                            if ($admin['ACLuser']) {
                                echo '<span class="badge badge-success">Consentito</span>';
                                //se non consentito 
                            } else {
                                echo '<span class="badge badge-danger">NON consentito</span>';
                            }
                            echo "</td>";

                            //acl admin
                            echo "<td>";
                            //se consentito
                            if ($admin['ACLadmin']) {
                                echo '<span class="badge badge-success">Consentito</span>';
                                //se non consentito 
                            } else {
                                echo '<span class="badge badge-danger">NON consentito</span>';
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

            //in caso non disponga dell'ACL admin
        } else {
            echo '<br><div class="alert alert-danger">
                    <strong>Errore!</strong> Non disponi dell\'autorizzazione per gestire i Bibliotecari.
                </div>';
        }
        ?>

    </div>

</body>

</html>