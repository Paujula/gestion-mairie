<?php
require_once("../config/connexion.php");


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
                OR d.emplacement LIKE ?
                OR d.statut LIKE ?
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
    <title>Recherche Documents</title>
</head>
<body>


<h2>Rechercher un document</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Rechercher (titre, cote, statut...)" required>
    <button type="submit">Rechercher</button>
</form>

<hr>

<?php if (!empty($documents)): ?>
    <?php foreach ($documents as $doc): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px;">

            <h3><?= htmlspecialchars($doc['titre']) ?></h3>

            <p><b>Analyse :</b> <?= htmlspecialchars($doc['analyse']) ?></p>
            <p><b>Statut :</b> <?= htmlspecialchars($doc['statut']) ?></p>
            <p><b>Emplacement :</b> <?= htmlspecialchars($doc['emplacement']) ?></p>
            <p><b>Catégorie :</b> <?= htmlspecialchars($doc['nom_serie']) ?></p>
            <p><b>Cote :</b> <?= htmlspecialchars($doc['cote']) ?></p>
            <p><b>Sous-série :</b> <?= htmlspecialchars($doc['libelle_sous_serie']) ?></p>

            <a href="consulter.php?id=<?= $doc['id_document'] ?>">
                Consulter
            </a>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun document trouvé</p>
<?php endif; ?>

</body>
</html>