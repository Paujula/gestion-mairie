<?php
session_start();
include("includes/header.php");
?>

<div style="max-width:800px; margin:40px auto; padding:20px; background:#f9f9f9; border-radius:10px;">

    <h1>📞 Contactez la Mairie</h1>

    <hr>

    <h3>🏛️ Informations officielles</h3>

    <p><b>Adresse :</b> Mairie de Dangbo, Ouémé – Bénin</p>
    <p><b>Email :</b> contact@dangbo.bj</p>
    <p><b>Téléphone :</b> +229 XX XX XX XX</p>
    <p><b>Horaires :</b> Lundi - Vendredi (08h - 17h30min)</p>

    <hr>

    <h3>✉️ Envoyer un message</h3>

    <form method="POST">

        <input type="text" name="nom" placeholder="Votre nom" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <input type="email" name="email" placeholder="Votre email" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <input type="text" name="sujet" placeholder="Sujet" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <textarea name="message" placeholder="Votre message" required style="width:100%; padding:8px; height:120px;"></textarea><br><br>

        <button style="width:100%; padding:10px; background:#2c3e50; color:white;">
            📩 Envoyer
        </button>

    </form>

</div>

