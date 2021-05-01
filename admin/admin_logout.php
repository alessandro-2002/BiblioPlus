<!DOCTYPE html>
<html lang="it">

<head>
    <title>Logout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <?php

    require_once('../assets/admin_session_login.php');

    //controllo se sta sloggando da tutte le sessioni attive
    if (isset($_GET['session']) && $_GET['session'] == "ALL") {

        try {
            $adminAccount->closeAllSessions();

            echo '<br><div class="alert alert-success">
                <strong>Logout effettuato!</strong> Logout effettuato con successo da tutte le sessioni, verrai reindirizzato alla home.
            </div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">
                <strong>Errore!</strong> Errore durante il logout, verrai reindirizzato alla home.

            </div>';
        }

        //altrimenti se sta sloggando solo da quella attuale
    } else {
        try {
            $adminAccount->logout();
            echo '<br><div class="alert alert-success">
                <strong>Logout effettuato!</strong> Logout effettuato con successo, verrai reindirizzato alla home.
            </div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">
                <strong>Errore!</strong> Errore durante il logout, verrai reindirizzato alla home.
            </div>';
        }
    }

    header('Refresh: 3; URL=index.php');

    ?>
</body>

</html>