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

      
      <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === "archiviste"): ?>
        <a href="<?= BASE_URL ?>documents/ajout_document.php"
            style="color:white; margin-right:15px;">
            Nos Documents
        </a>

        <a href="<?= BASE_URL ?>documents/demandes.php"
            style="color:white; margin-right:15px;">
            Liste des demandes
        </a>
    <?php endif; ?>

        <a href="<?= BASE_URL ?>apropos.php" style="color:white; margin-right:15px;">A propos</a>
        <a href="<?= BASE_URL ?>contact.php" style="color:white; margin-right:15px;">Contact</a>
        
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>administrateur/admin.php" style="color:white; margin-right:15px;">
                Admin
            </a>
        <?php endif; ?>

         <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'agent'): ?>
            <a href="<?= BASE_URL ?>documents/mes_demandes.php" style="color:white; margin-right:15px;">
                Mes Demandes
            </a>
        <?php endif; ?>



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