<?php ob_start(); ?>
<?php

//salvo location per activate solo su bottone della pagina
$fileName = basename($_SERVER['PHP_SELF']);
?>

<html>

<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">


    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
    <script src="../js/jquery-3.6.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>

<body>

    <?php
    //require del login e del db
    require_once("db.php");
    require_once("admin_session_login.php");
    ?>


    <header>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">

            <!-- logo -->
            <div class="navbar navbar-light">
                <a class="navbar-brand" href="index.php">
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

                    <!-- Dashboard -->
                    <li class="nav-item <?php
                                        //controllo se è su home, in tal caso metto active
                                        if ($fileName == "index.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>

                    <!-- Libri -->
                    <li class="nav-item <?php
                                        //controllo se è su utenti, in tal caso metto active
                                        if ($fileName == "admin_books.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="admin_books.php">Catalogo</a>
                    </li>

                    <!-- Utenti -->
                    <li class="nav-item <?php
                                        //controllo se è su utenti, in tal caso metto active
                                        if ($fileName == "admin_users.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="admin_users.php">Utenti</a>
                    </li>

                    <!-- Admins -->
                    <li class="nav-item <?php
                                        //controllo se è su admins, in tal caso metto active
                                        if ($fileName == "admin_admins.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="admin_admins.php">Bibliotecari</a>
                    </li>

                    <!-- Prestiti -->
                    <li class="nav-item <?php
                                        //controllo se è su prestiti, in tal caso metto active
                                        if ($fileName == "admin_loans.php") {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link" href="admin_loans.php">Prestiti</a>
                    </li>

                    <!-- user area -->
                    <li class="nav-item">
                        <a class="nav-link" href="/" target="_blank">User<span class="sr-only"></span></a>
                    </li>
                </ul>

                <!-- dropdown -->
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bibliotecario <?php echo $adminAccount->getName() . ' ' .
                                                    $adminAccount->getSurname(); ?>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="admin_change_password.php">Cambio password</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="admin_logout.php">Logout</a>
                                <a class="dropdown-item" href="admin_logout.php?session=ALL">Logout da tutte le sessioni</a>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <br>


</body>

</html>