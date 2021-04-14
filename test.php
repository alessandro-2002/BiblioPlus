<?php
session_start();
include_once("classes/user_class.php");
$account = new User();


$login = FALSE;

try
{
	$login = $account->login('toninelli.alessandro00@gmail.com', 'Stallman00');
}
catch (Exception $e)
{
	echo $e->getMessage();
	die();
}

if ($login)
{
	echo 'Authentication successful.';
	echo 'Account ID: ' . $account->getId() . '<br>';
	echo 'Account name: ' . $account->getMail() . '<br>';
}
else
{
	echo 'Authentication failed.';
}
