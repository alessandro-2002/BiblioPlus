<?php
$servername = "localhost";
$username = "PHP";
$password = "password";
$dbname = "biblio_plus";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch (PDOException $e) {
  $msg = '<br><div class="alert alert-danger">
            <strong>Errore!</strong> Errore di Connessione al Database
          </div>';

  die($msg);
}
