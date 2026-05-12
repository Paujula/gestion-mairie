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

<?php include("includes/header.php"); ?>

<h2>Recherche un document</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Veuillez entrer un terme pour vite retrouver un document" required>
    <button>Rechercher</button>
</form>

<hr>

<?php foreach ($documents as $doc): ?>

    <div style="border:1px solid #ccc; padding:10px; margin:10px;">

        <h3><?= htmlspecialchars($doc['cote']) ?></h3>

        <h3><?= htmlspecialchars($doc['titre']) ?></h3>

        <p><?= htmlspecialchars($doc['analyse']) ?></p>

        <a href="documents/consulter.php?id=<?= $doc['id_document'] ?>">
             Consulter
        </a>

    </div>

<?php endforeach; ?>