<?php
require_once("config/connexion.php");

$documents = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = "%" . trim($_GET['search']) . "%";

    $sql = "SELECT d.*, s.nom_serie, s.cote, ss.libelle_sous_serie
            FROM documents d
            JOIN serie_archives s ON d.id_serie = s.id_serie
            JOIN sous_serie ss ON s.id_sous_serie = ss.id_sous_serie
            WHERE 
                d.titre LIKE ?
                OR d.analyse LIKE ?
                OR d.statut LIKE ?
                OR d.emplacement LIKE ?
                OR d.date_enregistrement LIKE ?
                OR s.nom_serie LIKE ?
                OR s.cote LIKE ?
                OR ss.libelle_sous_serie LIKE ?";

    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        $search, $search, $search, $search,
        $search, $search, $search, $search
    ]);

    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<?php include("includes/header.php"); ?>

<div class="max-w-5xl mx-auto p-6">

  
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
         Rechercher un document
    </h2>

   
    <form method="GET" class="flex gap-3 mb-8">

        <input type="text" name="search" required
            placeholder="Tapez un mot-clé (titre, cote, série...)"
            class="flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">

        <button
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Rechercher
        </button>

    </form>

    
    <?php if (!empty($documents)): ?>

        <div class="grid md:grid-cols-2 gap-6">

            <?php foreach ($documents as $doc): ?>

                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">

                    <h3 class="text-lg font-bold text-blue-700 mb-2">
                         <?= htmlspecialchars($doc['cote']) ?>
                    </h3>

                    <h4 class="text-xl font-semibold text-gray-800 mb-2">
                        <?= htmlspecialchars($doc['titre']) ?>
                    </h4>

                    <p class="text-gray-600 text-sm mb-3">
                        <?= htmlspecialchars($doc['analyse']) ?>
                    </p>

                    <p class="text-sm text-gray-500 mb-2">
                         Série : <?= htmlspecialchars($doc['nom_serie']) ?>
                    </p>

                    <p class="text-sm text-gray-500 mb-4">
                         Sous-série : <?= htmlspecialchars($doc['libelle_sous_serie']) ?>
                    </p>

                    <a href="documents/consulter.php?id=<?= $doc['id_document'] ?>"
                       class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                         Consulter
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php elseif(isset($_GET['search'])): ?>

        <p class="text-center text-red-500 font-semibold">
            ❌ Aucun document trouvé
        </p>

    <?php endif; ?>

</div>

</body>
</html>