<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/gestion-mairie/');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Mairie</title>
</head>
<body>

<header style="background:#2c3e50; color:white; padding:15px; display:flex; justify-content:space-between;">

    <h2> Les archives de la mairie de Dangbo</h2>

    <nav>

        <a href="<?= BASE_URL ?>index.php" style="color:white; margin-right:15px;">
            Accueil
        </a>

      
        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?= BASE_URL ?>documents/ajout_document.php" style="color:white; margin-right:15px;">
                Nos Documents
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>auth/login.php" style="color:white; margin-right:15px;">
                Nos Documents
            </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>apropos.php" style="color:white; margin-right:15px;">A propos</a>
        <a href="<?= BASE_URL ?>contact.php" style="color:white; margin-right:15px;">Contact</a>

        <?php if (isset($_SESSION['user'])): ?>
            <span style="margin-right:10px;">
                👤 <?= htmlspecialchars($_SESSION['user']['email']) ?>
            </span>

            <a href="<?= BASE_URL ?>auth/logout.php" style="color:red;">
                Déconnexion
            </a>

        <?php else: ?>
            <a href="<?= BASE_URL ?>auth/login.php" style="color:yellow;">
                Connexion
            </a>
        <?php endif; ?>

    </nav>

</header>