<?php

//salvo location per activate solo su bottone della pagina
$fileName = basename($_SERVER['PHP_SELF']);
?>

<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
            <nav class="navbar navbar-light">
                <a class="navbar-brand" href="/">
                    <img src="../img/logo.svg" alt="" style="max-width: 60%;">
                </a>
            </nav>

            <!-- bottone per collapse in caso di schermo piccolo -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- navbar -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                    <!-- home -->
                    <li class="nav-item <?php
                                        //controllo se è su home, in tal caso metto active
                                        if ($fileName == "index.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>
                </ul>

                <?php

                if ($account->isAuthenticated()) {
                ?>
                    <div class="nav-item dropdown">
                        <!-- avatar -->
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="avatars/<?php
                                            if ($account->getAvatar() != NULL) {
                                                    echo $account->getAvatar();
                                                } else {
                                                    echo "no-avatar.jpg";
                                                }
                                                ?>" alt="" style="max-width: 60px;" class="rounded">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- eliminare pagine varie dell'area utente e metterle qui -->
                            <h6 class="dropdown-header">Benvenuto <?php echo htmlentities($account->getName()) . ' ' .
                                                                    htmlentities($account->getSurname()); ?></h6>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </div>

                <?php
                }
                ?>

            </div>
        </nav>
    </header>

</body>

</html>