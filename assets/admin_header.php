<html>

<head>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>

    <?php
    //require del login e del db
    require_once("db.php");
    require_once("admin_session_login.php");
    ?>

    <header style="background-color: #00e2ff66">

        <!-- logo -->
        <a href="index.php" class="logo">
            <img class="logo" src="../img/logo.svg" alt="Biblioteca Facile" />
        </a>

        <!-- menu di navigazione -->
        <div class="right">
            <div id="up">
                <!-- dati -->
                <nav id="userMenu" class="userMenu-content">
                    <ul>
                        <li>Benvenuto <?php echo htmlentities($adminAccount->getName()) . ' ' .
                                            htmlentities($adminAccount->getSurname()); ?> </li>
                        <li><a href='admin_logout.php'>Logout</a></li>
                    </ul>
                </nav>
            </div>

            <!-- div down per nav tra le pagine -->
            <div id="down">
                <?php
                //controlla la pagina in cui si è
                $fileName = basename($_SERVER['PHP_SELF']);

                //se sono nelle pagine di un nav evidenzio la casella                
                if ($fileName == "index.php") {
                    echo '<a class="nav active" href="index.php">Dashboard</a>';
                } else {
                    echo '<a class="nav" href="index.php">Dashboard</a>';
                }
                ?>

                <a class="nav" href="/" target="_blank">User</a>
                

            </div>
        </div>

    </header>

</body>

</html>