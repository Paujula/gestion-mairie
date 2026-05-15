<?php
session_start();
require_once("../config/connexion.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Document introuvable");
}


if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_doc'] = $id;
    header("Location: ../auth/login.php");
    exit();
}

$sql = "
    SELECT 
        d.*,
        s.cote,
        s.nom_serie,
        ss.libelle_sous_serie
    FROM documents d
    JOIN serie_archives s ON d.id_serie = s.id_serie
    JOIN sous_serie ss ON s.id_sous_serie = ss.id_sous_serie
    WHERE d.id_document = ?
";

$stmt = $connexion->prepare($sql);
$stmt->execute([$id]);

$doc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doc) {
    die("❌ Document inexistant");
}
?>

<?php include("../includes/header.php"); ?>

<h2> Fiche complète du document</h2>

<div style="border:1px solid #ccc; padding:20px; max-width:700px; margin:auto; background:#f9f9f9;">


    <p><b> Cote :</b> <?= htmlspecialchars($doc['cote']) ?></p>

     <h3> <?= htmlspecialchars($doc['titre']) ?></h3>

    <p><b> Analyse :</b> <?= htmlspecialchars($doc['analyse']) ?></p>

    <p><b> Date d’enregistrement :</b> <?= htmlspecialchars($doc['date_enregistrement']) ?></p>

     <p><b> Série :</b> <?= htmlspecialchars($doc['nom_serie']) ?></p>

    <p><b> Sous-série :</b> <?= htmlspecialchars($doc['libelle_sous_serie']) ?></p>

    <p><b> Statut :</b> <?= htmlspecialchars($doc['statut']) ?></p>

    <p><b> Emplacement :</b> <?= htmlspecialchars($doc['emplacement']) ?></p>



    <p>
        <b>📎 Fichier :</b><br>
        <a href="../uploads/<?= htmlspecialchars($doc['fichier']) ?>" target="_blank">
            📥 Télécharger le document
        </a>
    </p>

     <a href="ajout_document.php?id=<?= $doc['id_document'] ?>" style="color:green;">
         Retour à la liste
    </a>

</div>
   