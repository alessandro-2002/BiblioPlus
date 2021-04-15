<?php

require_once("db.php");
require_once('classes/user_class.php');

/* avvia sessione */
session_start();

/* crea un oggetto account */
$account = new User();

//login di sessione
$login = $account->sessionLogin();