<html>

<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>

    <?php
    //require del login e del db
    require_once("db.php");
    require_once("check_login.php");
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
                //controllo autenticazione dell'utente e in caso metto i dati
                if ($account->isAuthenticated()) {
                    echo "ciao " . $account->getName();
                } else {
                ?>
                    <a href="login.php">Accedi o registrati</a>
                <?php
                }
                ?>
                <!-- Benvenuto tizio! -->
            </div>

            <!-- div down per nav tra le pagine -->
            <div id="down">
                <?php
                //controlla la pagina in cui si è
                $fileName = basename($_SERVER['PHP_SELF']);

                //se sono nelle pagine di un nav evidenzio la casella                
                if ($fileName == "index.php") {
                    echo '<a class="active" href="/">Catalogo</a>';
                } else {
                    echo '<a href="/">Catalogo</a>';
                }

                if ($fileName == "contacts.php") {
                    echo '<a class="active" href="contacts.php">Contatti</a>';
                } else {
                    echo '<a href="contacts.php">Contatti</a>';
                }
                ?>

                <a href="admin">Admin</a>


            </div>
        </div>

    </header>

</body>

</html>