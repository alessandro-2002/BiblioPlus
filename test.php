<?php
session_start();
include_once("classes/user_class.php");
$account = new User();

$login = FALSE;

try {
    $login = $account->sessionLogin();
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

if ($login) {
    echo 'Authentication successful.';
    echo 'Account ID: ' . $account->getId() . '<br>';
    echo 'Account name: ' . $account->getName() . '<br>';
} else {
    echo 'Authentication failed.';
}
