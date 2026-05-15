<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== "archiviste") {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Document introuvable");
}

$sql = "
SELECT d.*, s.cote, s.nom_serie, ss.libelle_sous_serie,
       s.id_serie, ss.id_sous_serie
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cote = $_POST['cote'];

    $titre = $_POST['titre'];
    $analyse = $_POST['analyse'];
    $date_enregistrement = $_POST['date_enregistrement'];
    $statut = $_POST['statut'];
    $emplacement = $_POST['emplacement'];

   
    
    $nom_serie = $_POST['nom_serie'];

  
    $libelle_sous_serie = $_POST['libelle_sous_serie'];

    $fichier = $doc['fichier'];

    if (!empty($_FILES['fichier']['name'])) {
        $fichier = time() . "_" . $_FILES['fichier']['name'];
        move_uploaded_file($_FILES['fichier']['tmp_name'], "../uploads/" . $fichier);
    }

    try {
        $connexion->beginTransaction();

      
        $stmt1 = $connexion->prepare("
            UPDATE sous_serie 
            SET libelle_sous_serie = ?
            WHERE id_sous_serie = ?
        ");
        $stmt1->execute([
            $libelle_sous_serie,
            $doc['id_sous_serie']
        ]);

       
        $stmt2 = $connexion->prepare("
            UPDATE serie_archives 
            SET cote = ?, nom_serie = ?
            WHERE id_serie = ?
        ");
        $stmt2->execute([
            $cote,
            $nom_serie,
            $doc['id_serie']
        ]);

        $stmt3 = $connexion->prepare("
            UPDATE documents 
            SET titre = ?,
                analyse = ?,
                date_enregistrement = ?,
                statut = ?,
                emplacement = ?,
                fichier = ?
            WHERE id_document = ?
        ");

        $stmt3->execute([
            $titre,
            $analyse,
            $date_enregistrement,
            $statut,
            $emplacement,
            $fichier,
            $id
        ]);

        $connexion->commit();

        echo "<script>
            alert('📄 Document modifié avec succès');
            window.location.href = 'ajout_document.php';
        </script>";
        exit();

    } catch (Exception $e) {
        $connexion->rollBack();
        die("Erreur : " . $e->getMessage());
    }
}
?>

<?php include("../includes/header.php"); ?>

<div style="max-width:750px;margin:30px auto;background:#f4f4f4;padding:20px;border-radius:10px;">

<h2> Modifier un document </h2>

<form method="POST" enctype="multipart/form-data">

  

    <label>Cote</label>
    <input type="text" name="cote" value="<?= htmlspecialchars($doc['cote']) ?>" required style="width:100%;padding:8px;"><br><br>

    <label>Titre</label>
    <input type="text" name="titre" value="<?= htmlspecialchars($doc['titre']) ?>" required style="width:100%;padding:8px;"><br><br>

    <label>Analyse</label>
    <textarea name="analyse" style="width:100%;padding:8px;"><?= htmlspecialchars($doc['analyse']) ?></textarea><br><br>

    <label>Date</label>
    <input type="date" name="date_enregistrement" value="<?= $doc['date_enregistrement'] ?>" style="width:100%;padding:8px;"><br><br>

    <label>Nom série</label>
    <input type="text" name="nom_serie" value="<?= htmlspecialchars($doc['nom_serie']) ?>" required style="width:100%;padding:8px;"><br><br>

    <label>Libellé sous-série</label>
    <input type="text" name="libelle_sous_serie" value="<?= htmlspecialchars($doc['libelle_sous_serie']) ?>" required style="width:100%;padding:8px;"><br><br>

    <label>Statut</label>
    <select name="statut" style="width:100%;padding:8px;">
        <option value="confidentiel" <?= $doc['statut']=="confidentiel"?"selected":"" ?>>Confidentiel</option>
        <option value="non confidentiel" <?= $doc['statut']=="non confidentiel"?"selected":"" ?>>Non confidentiel</option>
    </select><br><br>

    <label>Emplacement</label>
    <input type="text" name="emplacement" value="<?= htmlspecialchars($doc['emplacement']) ?>" style="width:100%;padding:8px;"><br><br>

    <label>Fichier</label>
    <input type="file" name="fichier"><br><br>

    <button type="submit" style="width:100%;padding:10px;background:#f39c12;color:white;">
        Modifier ce document
    </button>

</form>

</div>