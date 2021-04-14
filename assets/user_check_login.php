<?php

require_once("db.php");
require_once('classes/user_class.php');

/* avvia sessione */
session_start();

/* crea un oggetto account */
$account = new User();

//login di sessione
$login = $account->sessionLogin();

/*
try {
    //controllo se non loggato lo reindirizzo al login
    if (!$login) {
        header('Location: ../user/index.php');
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

*/