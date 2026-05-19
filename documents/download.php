<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_user = $_SESSION['user']['id_utilisateur'];
$role = $_SESSION['user']['role'];

$id_document = $_GET['id_document'] ?? 0;


if ($role === 'admin' || $role === 'archiviste') {
    $autorise = true;
} else {



    $stmt = $connexion->prepare("
        SELECT * FROM acces_documents
        WHERE id_document = ? AND id_utilisateur = ?
    ");
    $stmt->execute([$id_document, $id_user]);

    $autorise = $stmt->rowCount() > 0;
}



if (!$autorise) {
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès refusé</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white shadow-lg rounded-lg p-6 text-center max-w-md w-full">

    <h2 class="text-xl font-bold text-red-600 mb-3">
        ⛔ Accès refusé
    </h2>

    <p class="text-gray-600 mb-4">
        Vous n'avez pas le droit d'accéder à ce document.
        <br>
        Veuillez faire une demande d'accès.
    </p>

    <div class="flex flex-col gap-3">


        <a href="demande_acces.php?id_document=<?= $id_document ?>"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
           📩 Faire une demande d'accès
        </a>

        <a href="../index.php"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           ⬅ Aller à l'accueil
        </a>

    </div>

</div>

</body>
</html>

<?php
    exit();
}



if (!isset($_GET['file'])) {
    die("❌ Fichier introuvable");
}

$fichier = basename($_GET['file']);
$chemin = "../uploads/" . $fichier;

if (file_exists($chemin)) {

    $connexion->prepare("
        INSERT INTO historique (action, date_action, id_document, id_utilisateur)
        VALUES ('Téléchargement', NOW(), ?, ?)
    ")->execute([$id_document, $id_user]);

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . $fichier . "\"");
    header("Content-Length: " . filesize($chemin));

    readfile($chemin);
    exit();

} else {
    echo "❌ Fichier introuvable";
}