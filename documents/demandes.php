<?php
session_start();
require_once("../config/connexion.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'archiviste') {
    die("⛔ Accès refusé");
}

try {

    $stmt = $connexion->prepare("
        SELECT d.*, u.nom, u.prenom, doc.titre, s.nom_service
        FROM demandes d
        JOIN utilisateurs u ON d.id_utilisateur = u.id_utilisateur
        JOIN documents doc ON d.id_document = doc.id_document
        JOIN service s ON d.id_service = s.id_service
        ORDER BY d.date_demande DESC
    ");

    $stmt->execute();
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes reçues</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">

<h2 class="text-xl font-bold mb-4">📩 Demandes reçues</h2>

<table class="w-full bg-white shadow rounded text-center">

    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Utilisateur</th>
            <th class="p-2">Document</th>
            <th class="p-2">Objet</th>
            <th class="p-2">Service</th>
            <th class="p-2">Action</th>
        </tr>
    </thead>

    <tbody>

    <?php if (!empty($demandes)) : ?>

        <?php foreach ($demandes as $d) : ?>
            <tr class="border-t">

                <td class="p-2">
                    <?= htmlspecialchars($d['nom']) ?> <?= htmlspecialchars($d['prenom']) ?>
                </td>

                <td class="p-2">
                    <?= htmlspecialchars($d['titre']) ?>
                </td>

                <td class="p-2">
                    <?= htmlspecialchars($d['objet']) ?>
                </td>

                <td class="p-2">
                    <?= htmlspecialchars($d['nom_service']) ?>
                </td>

                <td class="p-2 space-x-2">

                    <a href="accepter.php?id=<?= $d['id_demande'] ?>"
                       class="bg-green-600 text-white px-3 py-1 rounded">
                        ✔ Accepter
                    </a>

                    <a href="refuser.php?id=<?= $d['id_demande'] ?>"
                       class="bg-red-600 text-white px-3 py-1 rounded">
                        ✖ Refuser
                    </a>

                </td>

            </tr>
        <?php endforeach; ?>

    <?php else : ?>

        <tr>
            <td colspan="5" class="p-4 text-gray-500">
                Aucune demande trouvée
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

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