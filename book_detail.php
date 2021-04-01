<?php

//controllo che sia passato un ISBN in get altrimenti torno su index
if (!isset($_GET['ISBN']) || $_GET['ISBN'] == "")
    header("Location: index.php");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/base.css">
    <title>Biblioteca facile!</title>
</head>

<body>

    <div class="content">
        <?php
        require_once('assets/header.php');
        ?>
    </div>

</body>

</html>