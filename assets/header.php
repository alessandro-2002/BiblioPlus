<html>

<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/user_menu.js"></script>
</head>

<body>

    <?php
    //require del login e del db
    require_once("db.php");
    require_once("session_login.php");
    ?>

    <header>

        <!-- logo -->
        <a href="/" class="logo">
            <img class="logo" src="img/logo.jpg" alt="Biblioteca Facile" />
        </a>

        <!-- menu di navigazione -->
        <div class="right">

            <!-- div up dedicato al login/gestione utente -->
            <div id="up">
                <?php
                //controllo autenticazione dell'utente e in caso metto il menù
                if ($account->isAuthenticated()) {
                    //cover 
                    echo "<img style='width:60px; heigth=60px;' src='avatars/";
                    if ($account->getAvatar() != NULL) {
                        echo $account->getAvatar();
                    } else {
                        echo "no-avatar.jpg";
                    }
                    echo "' />";

                ?>
                    <!-- button per aprire menù -->
                    <button id="menuButton" onclick="userMenuDrop()">Menu</button>

                    <!-- menù -->
                    <nav id="userMenu" class="userMenu-content" style="display: none;">
                        <ul>
                            <li>Benvenuto <?php echo htmlentities($account->getName(), ENT_HTML5, 'ISO-8859-1') . ' ' .
                                                htmlentities($account->getSurname(), ENT_HTML5, 'ISO-8859-1'); ?> </li>
                            <li><a href='user_area.php'>Area Riservata</a></li>
                            <li><a href='logout.php'>Logout</a></li>
                        </ul>
                    </nav>
                <?php
                } else {
                    echo '<a class="nav" href="login.php">Accedi o registrati</a>';
                }
                ?>
            </div>

            <!-- div down per nav tra le pagine -->
            <div id="down">
                <?php
                //controlla la pagina in cui si è
                $fileName = basename($_SERVER['PHP_SELF']);

                //se sono nelle pagine di un nav evidenzio la casella                
                if ($fileName == "index.php") {
                    echo '<a class="nav active" href="/">Catalogo</a>';
                } else {
                    echo '<a class="nav" href="/">Catalogo</a>';
                }

                if ($fileName == "contacts.php") {
                    echo '<a class="nav active" href="contacts.php">Contatti</a>';
                } else {
                    echo '<a class="nav" href="contacts.php">Contatti</a>';
                }
                ?>

                <a class="nav" href="admin">Admin</a>


            </div>
        </div>

    </header>

</body>

</html>