<?php
session_start();
include("includes/header.php");
?>

<div style="max-width:900px; margin:40px auto; padding:20px; background:#f9f9f9; border-radius:10px;">

    <h1>🏛️ À propos du système de gestion des archives</h1>

    <hr>

    <h2>📌 Présentation</h2>
    <p>
        Le système de gestion des archives de la mairie est une application web développée pour moderniser
        la gestion des documents administratifs. Il permet de stocker, organiser et consulter les documents
        de manière sécurisée et efficace.
    </p>

    <h2>🎯 Objectif du système</h2>
    <ul>
        <li>Centraliser tous les documents administratifs</li>
        <li>Faciliter la recherche des archives</li>
        <li>Sécuriser l’accès aux documents sensibles</li>
        <li>Améliorer la gestion interne de la mairie</li>
    </ul>

    <h2>👥 Utilisateurs du système</h2>
    <ul>
        <li>👨‍💼 Administrateur</li>
        <li>📁 Archiviste</li>
        <li>🏢 Agents de la mairie</li>
    </ul>

    <h2>🔐 Sécurité</h2>
    <p>
        L’accès aux documents est sécurisé par authentification.
        Certaines actions comme l’ajout ou la consultation détaillée nécessitent une connexion.
    </p>

    <h2>📍 Contexte</h2>
    <p>
        Ce projet s’inscrit dans la digitalisation des services administratifs des mairies,
        notamment dans des communes comme celle de Dangbo (Ouémé - Bénin).
    </p>

    <hr>

    <p style="text-align:center; font-weight:bold;">
        © <?= date("Y") ?> - Système de Gestion des Archives de la Mairie de Dangbo
    </p>

</div>

