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

try {

    $stmt = $connexion->prepare("
        UPDATE demandes
        SET statut_demande = 'approuve'
        WHERE id_demande = ?
    ");

    $stmt->execute([$id_demande]);

    header("Location: demandes.php?msg=approuve");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}