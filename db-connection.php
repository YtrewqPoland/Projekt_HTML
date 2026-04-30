<?php
$host = "localhost";
$dbname = "siminski_strona";
$user = "adminSiminski";
$pass = "admin";

$dsn = 'mysql:host=localhost;dbname=siminski_strona;';

try{
    $pdo = new PDO($dsn, $user, $pass);
}catch(PDOException $e){
    echo "Błąd połączenia: " . $e->getMessage();
}
?>