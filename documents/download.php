<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_user = $_SESSION['user']['id_utilisateur'];
$role = $_SESSION['user']['role'];

$id_document = isset($_GET['id_document']) ? (int)$_GET['id_document'] : 0;
$fichier = $_GET['file'] ?? null;


$stmt = $connexion->prepare("SELECT * FROM documents WHERE id_document = ?");
$stmt->execute([$id_document]);
$doc = $stmt->fetch();

if (!$doc) {
    die("Document introuvable.");
}


if ($role === 'admin' || $role === 'archiviste') {
    $chemin = "../uploads/" . $doc['fichier'];

    if (file_exists($chemin)) {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($chemin));
        readfile($chemin);
        exit();
    } else {
        die("Fichier introuvable.");
    }
}

$stmt = $connexion->prepare("
    SELECT statut_demande 
    FROM demandes
    WHERE id_document = ? AND id_utilisateur = ?
    ORDER BY date_demande DESC
    LIMIT 1
");
$stmt->execute([$id_document, $id_user]);
$demande = $stmt->fetch();


$stmt = $connexion->prepare("
    SELECT 1 FROM demandes
    WHERE id_document = ? 
    AND id_utilisateur = ?
    AND statut_demande = 'accepte'
    LIMIT 1
");
$stmt->execute([$id_document, $id_user]);
$autorise = $stmt->fetch();

if (!$autorise) {
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">

<div class="bg-white p-6 rounded shadow text-center w-96">

    <h2 class="text-red-600 font-bold text-xl">⛔ Accès refusé</h2>

    <?php if (!$demande): ?>
        <p>Vous n'avez pas encore fait de demande.</p>
    <?php elseif ($demande['statut_demande'] == 'en_attente'): ?>
        <p>⏳ Votre demande est en attente.</p>
    <?php else: ?>
        <p>❌ Votre demande a été refusée.</p>
    <?php endif; ?>

    <a href="demande_acces.php?id_document=<?= $id_document ?>"
       class="bg-green-600 text-white px-4 py-2 rounded block mt-4">
        📩 Faire une demande
    </a>

    <a href="../index.php"
       class="bg-blue-600 text-white px-4 py-2 rounded block mt-2">
        ⬅ Retour
    </a>

</div>

</body>
</html>

<?php
exit();
}

$chemin = "../uploads/" . $doc['fichier'];

if (file_exists($chemin)) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . basename($chemin));
    readfile($chemin);
    exit();
}

echo "Fichier introuvable.";
?>