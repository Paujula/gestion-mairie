<?php
session_start();
require_once("../config/connexion.php");

$id_user = $_SESSION['user']['id_utilisateur'];

$stmt = $connexion->prepare("
SELECT 
    d.*, 
    doc.titre,
    s.nom_service
FROM demandes d
JOIN documents doc ON d.id_document = doc.id_document
LEFT JOIN service s ON d.id_service = s.id_service
WHERE d.id_utilisateur = ?
ORDER BY d.date_demande DESC
");

$stmt->execute([$id_user]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">

<h2 class="text-xl font-bold mb-4">📋 Mes demandes</h2>

<table class="w-full bg-white shadow rounded">

<tr class="bg-gray-200">
    <th>Titre</th>
    <th>Objet</th>
    <th>Service</th>
    <th>Statut</th>
</tr>

<?php foreach ($demandes as $d): ?>
<tr class="border-t text-center">

<td><?= $d['titre'] ?></td>
<td><?= $d['objet'] ?></td>

<td><?= $d['nom_service'] ?? 'Non défini' ?></td>

<td>
<?php if ($d['statut_demande'] == 'en_attente'): ?>
    <span class="text-orange-500">⏳ En attente</span>

<?php elseif ($d['statut_demande'] == 'accepte'): ?>
    <span class="text-green-600">✔ Acceptée</span>

<?php else: ?>
    <span class="text-red-600">❌ Refusée</span>
<?php endif; ?>
</td>

</tr>
<?php endforeach; ?>

</table>

<div class="text-center mt-6">
    <a href="../index.php"
       class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg 
              hover:bg-orange-600 transition duration-300 shadow">
        ⬅ Retour à l'accueil
    </a>
</div>

</body>
</html>