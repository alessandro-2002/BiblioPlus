<html>

<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>

    <?php
    require_once("db.php");
    ?>
    
    <header>

        <a href="/" class="logo">
            <img class="logo" src="img/logo.jpg" alt="Biblioteca Facile" />
        </a>
        <div class="right">
            <div id="up">
                <a href="login.php">Accedi o registrati</a>
                <!-- Benvenuto tizio! -->
            </div>
            <div id="down">
                <?php
                $fileName = basename($_SERVER['PHP_SELF']);


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