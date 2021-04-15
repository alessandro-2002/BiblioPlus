<?php

require_once("db.php");
require_once('classes/user_class.php');

/* avvia sessione */
session_start();

/* crea un oggetto account */
$account = new User();

//login di sessione
$login = $account->sessionLogin();

//se autenticazione andata a buon fine faccio redirect a cambio di password se scaduta
if ($login) {
    $expirationDate = new DateTime($account->getExpiration());
    $now = new DateTime();

    //controllo scadenza
    if ($expirationDate < $now) {
        //redirect SE non sono già nella pagine change_password
        $filename = basename($_SERVER['PHP_SELF']);
        if ($filename != "change_password.php") {
            header('Location: change_password.php');
        }
    }
}
