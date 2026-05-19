<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'archiviste') {
    die("⛔ Accès refusé");
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ ID invalide");
}

$id_demande = intval($_GET['id']);


$motif = "Accès refusé par l'administration";

try {

    $stmt = $connexion->prepare("
        UPDATE demandes
        SET statut_demande = 'refuse',
            motif_refus = ?
        WHERE id_demande = ?
    ");

    $stmt->execute([$motif, $id_demande]);

    header("Location: demandes.php?msg=refuse");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}