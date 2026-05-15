<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ ID invalide");
    
}

$stmt = $connexion->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);

header("Location: admin.php");
exit();