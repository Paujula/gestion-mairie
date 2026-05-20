<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("⛔ Accès refusé");
}

$stmt = $connexion->query("
SELECT 
    h.id_historique,
    h.action,
    h.date_action,
    u.nom,
    d.titre
FROM historique h
JOIN utilisateurs u ON h.id_utilisateur = u.id_utilisateur
JOIN documents d ON h.id_document = d.id_document
ORDER BY h.date_action DESC
");

$historiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        📜 Historique des activités
    </h2>

    <table class="w-full border-collapse">

        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-3">Utilisateur</th>
                <th class="p-3">Document</th>
                <th class="p-3">Action</th>
                <th class="p-3">Date</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!empty($historiques)) : ?>
            <?php foreach ($historiques as $h) : ?>
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">
                        <?= htmlspecialchars($h['nom']) ?>
                    </td>

                    <td class="p-3">
                        <?= htmlspecialchars($h['titre']) ?>
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-700">
                            <?= htmlspecialchars($h['action']) ?>
                        </span>
                    </td>

                    <td class="p-3 text-gray-600">
                        <?= htmlspecialchars($h['date_action']) ?>
                    </td>

                </tr>
            <?php endforeach; ?>
        <?php else : ?>

            <tr>
                <td colspan="4" class="text-center p-6 text-gray-500">
                    Aucun historique disponible
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

        <div class="text-center mt-6">
            <a href="../index.php"
            class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg 
                    hover:bg-orange-600 transition duration-300 shadow">
                ⬅ Retour à l'accueil
            </a>
        </div>

</body>
</html>