<?php
session_start();
require_once("../config/connexion.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_document = $_GET['id_document'] ?? null;

if (!$id_document) {
    die("❌ Document introuvable");
}

$id_user = $_SESSION['user']['id_utilisateur'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $objet = $_POST['objet'];
    $id_service = $_POST['id_service'];

    $stmt = $connexion->prepare("
        INSERT INTO demandes (date_demande, objet, statut_demande, id_document, id_utilisateur, id_service)
        VALUES (NOW(), ?, 'en_attente', ?, ?, ?)
    ");

    $stmt->execute([$objet, $id_document, $id_user, $id_service]);

    header("Location: mes_demandes.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<form method="POST" class="bg-white p-6 rounded shadow w-96">

    <h2 class="text-xl font-bold mb-4">📩 Demande d'accès</h2>

    <textarea name="objet" class="w-full border p-2 rounded mb-3" required></textarea>


    <select name="id_service" class="w-full border p-2 rounded mb-4" required>
        <option value="">-- Choisir un service --</option>

        <?php
        $services = $connexion->query("SELECT * FROM service");
        while ($s = $services->fetch()) {
            echo "<option value='".$s['id_service']."'>".$s['nom_service']."</option>";
        }
        ?>
    </select>

    <button class="bg-green-600 text-white px-4 py-2 rounded w-full">
        Envoyer la demande
    </button>

</form>

</body>
</html>