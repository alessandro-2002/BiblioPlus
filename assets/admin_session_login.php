<?php

require_once("db.php");
require_once('../classes/admin_class.php');

/* avvia sessione */
session_start();

/* crea un oggetto account */
$adminAccount = new Admin();

//login di sessione
$login = $adminAccount->sessionLogin();

//se autenticazione andata a buon fine faccio redirect a cambio di password se scaduta
if ($login) {
    $expirationDate = new DateTime($adminAccount->getExpiration());
    $now = new DateTime();

    //controllo scadenza
    if ($expirationDate < $now) {
        //redirect SE non sono già nella pagine change_password
        $filename = basename($_SERVER['PHP_SELF']);
        if ($filename != "admin_change_password.php") {
            header('Location: ../admin/admin_change_password.php');
        }
    }

    //se autenticazione non è andata a buon fine rimando a login 
} else {
    //controllo non sia già su pagine di login per non creare redirect in loop
    $filename = basename($_SERVER['PHP_SELF']);
    if ($filename != "admin_login.php") {
        header('Location: ../admin/admin_login.php');
    }
}
