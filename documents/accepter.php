<?php
session_start();
require_once("../config/connexion.php");

if ($_SESSION['user']['role'] !== 'archiviste') {
    die("Accès refusé");
}

$id = $_GET['id'];

$connexion->prepare("
UPDATE demandes
SET statut_demande = 'accepte',
    date_reponse = NOW()
WHERE id_demande = ?
")->execute([$id]);

header("Location: demandes.php");
exit();