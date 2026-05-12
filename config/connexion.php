<?php

$host = "localhost";
$dbname = "archives_mairie";
$username = "root"; 
$password = "";    

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);


    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>