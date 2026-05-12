<?php
session_start();
require_once("../config/connexion.php");


if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['user']['role'] !== "archiviste") {
    die("❌ Accès refusé : archiviste uniquement");
}

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titre = $_POST['titre'];
    $analyse = $_POST['analyse'];
    $date_enregistrement = $_POST['date_enregistrement'];
    $statut = $_POST['statut'];
    $emplacement = $_POST['emplacement'];

    $cote = $_POST['cote'];
    $nom_serie = $_POST['nom_serie'];
    $libelle_sous_serie = $_POST['libelle_sous_serie'];

    $fichier = time() . "_" . $_FILES['fichier']['name'];
    $tmp = $_FILES['fichier']['tmp_name'];

    $destination = "../uploads/" . $fichier;

    if (move_uploaded_file($tmp, $destination)) {

        try {
            $connexion->beginTransaction();

           
            $stmt = $connexion->prepare("INSERT INTO sous_serie(libelle_sous_serie) VALUES(?)");
            $stmt->execute([$libelle_sous_serie]);
            $id_sous_serie = $connexion->lastInsertId();

         
            $stmt = $connexion->prepare("INSERT INTO serie_archives(cote, nom_serie, id_sous_serie) VALUES(?,?,?)");
            $stmt->execute([$cote, $nom_serie, $id_sous_serie]);
            $id_serie = $connexion->lastInsertId();

          
            $stmt = $connexion->prepare("
                INSERT INTO documents(
                    titre,
                    analyse,
                    date_enregistrement,
                    statut,
                    emplacement,
                    fichier,
                    id_serie
                ) VALUES (?,?,?,?,?,?,?)
            ");

            $stmt->execute([
                $titre,
                $analyse,
                $date_enregistrement,
                $statut,
                $emplacement,
                $fichier,
                $id_serie
            ]);

            $connexion->commit();

            $message = "✅ Document ajouté avec succès";

        } catch (Exception $e) {
            $connexion->rollBack();
            $message = "❌ Erreur : " . $e->getMessage();
        }

    } else {
        $message = "❌ Erreur upload fichier";
    }
}


$stmt = $connexion->query("
    SELECT d.*, s.cote, s.nom_serie, ss.libelle_sous_serie
    FROM documents d
    JOIN serie_archives s ON d.id_serie = s.id_serie
    JOIN sous_serie ss ON s.id_sous_serie = ss.id_sous_serie
    ORDER BY d.id_document DESC
");

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../includes/header.php"); ?>

<h2> Ajouter un document</h2>

<p style="color:green; font-weight:bold;">
    <?= $message ?>
</p>


<form method="POST" enctype="multipart/form-data" style="max-width:700px; margin:auto;">

    <div>
        <label>Cote</label><br>
        <input type="text" name="cote" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Titre</label><br>
        <input type="text" name="titre" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Analyse</label><br>
        <textarea name="analyse" style="width:100%; padding:8px;"></textarea>
    </div><br>

    <div>
        <label>Date d’enregistrement</label><br>
        <input type="date" name="date_enregistrement" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Nom de la série</label><br>
        <input type="text" name="nom_serie" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Libellé sous-série</label><br>
        <input type="text" name="libelle_sous_serie" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Statut</label><br>
        <select name="statut" required style="width:100%; padding:8px;">
            <option value="confidentiel">Confidentiel</option>
            <option value="non confidentiel">Non confidentiel</option>
        </select>
    </div><br>

    <div>
        <label>Emplacement</label><br>
        <input type="text" name="emplacement" required style="width:100%; padding:8px;">
    </div><br>

    <div>
        <label>Fichier</label><br>
        <input type="file" name="fichier" required style="width:100%; padding:8px;">
    </div><br>

    <button type="submit" style="width:100%; padding:10px; background:#2c3e50; color:white;">
        ➕ Ajouter document
    </button>

</form>

<hr>


<h2> Liste complète des documents enrégistrés</h2>

<?php foreach ($documents as $doc): ?>

<div style="border:1px solid #ccc; margin:15px; padding:15px; background:#f9f9f9;">

    <h3> <?= htmlspecialchars($doc['titre']) ?></h3>

    <p><b> Cote :</b> <?= htmlspecialchars($doc['cote']) ?></p>
    <p><b> Titre :</b> <?= htmlspecialchars($doc['titre']) ?></p> 
    <p><b> Analyse :</b> <?= htmlspecialchars($doc['analyse']) ?></p> 
    <p><b> Date :</b> <?= htmlspecialchars($doc['date_enregistrement']) ?></p>
    <p><b> Série :</b> <?= htmlspecialchars($doc['nom_serie']) ?></p>
    <p><b> Sous-série :</b> <?= htmlspecialchars($doc['libelle_sous_serie']) ?></p>
    <p><b> Statut :</b> <?= htmlspecialchars($doc['statut']) ?></p>
    <p><b> Emplacement :</b> <?= htmlspecialchars($doc['emplacement']) ?></p>


    <p>
        <b>Fichier :</b>
        <a href="../uploads/<?= htmlspecialchars($doc['fichier']) ?>" target="_blank">
            Télécharger
        </a>
    </p>

    <a href="consulter.php?id=<?= $doc['id_document'] ?>">
         Consulter
    </a>

</div>

<?php endforeach; ?>