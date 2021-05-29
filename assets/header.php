<?php

//salvo location per activate solo su bottone della pagina
$fileName = basename($_SERVER['PHP_SELF']);
?>

<html>

<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


</head>

<body>

    <?php
    //require del login e del db
    require_once("db.php");
    require_once("session_login.php");
    ?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f0f0f0;">

            <!-- logo -->
            <div class="navbar navbar-light">
                <a class="navbar-brand" href="/">
                    <img src="../img/logo.svg" alt="" style="max-width: 60%;">
                </a>
            </div>

            <!-- bottone per collapse in caso di schermo piccolo -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- navbar -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto nav-pills">

                    <!-- home -->
                    <li class="nav-item <?php
                                        //controllo se è su home, in tal caso metto active
                                        if ($fileName == "index.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/" target="_blank">Admin<span class="sr-only"></span></a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php

                    if ($account->isAuthenticated()) {
                    ?>
                        <li>
                            <div class="nav-item dropdown">
                                <!-- avatar -->
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="avatars/<?php
                                                        if ($account->getAvatar() != NULL) {
                                                            echo $account->getAvatar();
                                                        } else {
                                                            echo "no-avatar.jpg";
                                                        }
                                                        ?>" alt="" style="max-width: 100px; height: 60px;" class="rounded">
                                </a>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <!-- eliminare pagine varie dell'area utente e metterle qui -->
                                    <h6 class="dropdown-header">Benvenuto <?php echo $account->getName() . ' ' .
                                                                                $account->getSurname(); ?></h6>
                                    <a class="dropdown-item" href="profile.php">Profilo</a>
                                    <a class="dropdown-item" href="change_password.php">Cambio password</a>
                                    <a class="dropdown-item" href="loans.php">Prestiti</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                    <a class="dropdown-item" href="logout.php?session=ALL">Logout da tutte le sessioni</a>
                                </div>
                            </div>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Accedi o registrati<span class="sr-only"></span></a>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
            </div>
        </nav>
    </header>
    <br>

</body>

</html>