<?php
require_once("../config/connexion.php");

$id = $_GET['id'] ?? null;

if ($id) {


    $stmt = $connexion->prepare("SELECT fichier FROM documents WHERE id_document=?");
    $stmt->execute([$id]);
    $doc = $stmt->fetch();

    if ($doc) {
        $file = "../uploads/" . $doc['fichier'];

        if (file_exists($file)) {
            unlink($file); 
        }


        $stmt = $connexion->prepare("DELETE FROM documents WHERE id_document=?");
        $stmt->execute([$id]);

        echo "<script>
            alert('🗑️ Document supprimé avec succès');
            window.location.href = 'ajout_document.php';
        </script>";
        exit();
    }
}

echo "<script>
    alert('❌ Erreur lors de la suppression');
    window.location.href = 'ajout_document.php';
</script>";
exit();
?>