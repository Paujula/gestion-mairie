<?php
session_start();
require_once("../config/connexion.php");

include("../includes/header.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";


if (isset($_POST['ajouter'])) {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    $check = $connexion->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "❌ Email déjà utilisé";
    } else {
        $sql = $connexion->prepare("
            INSERT INTO utilisateurs(nom, prenom, email, mot_de_passe, role)
            VALUES (?, ?, ?, ?, ?)
        ");
        $sql->execute([$nom, $prenom, $email, $mot_de_passe, $role]);

        $message = "✅ Utilisateur ajouté avec succès";
    }
}

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $sql = $connexion->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    $sql->execute([$id]);

    header("Location: admin.php");
    exit();
}


$users = $connexion->query("SELECT * FROM utilisateurs ORDER BY role ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto p-4">

   
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">
        Gestion des utilisateurs
    </h2>

  
    <?php if ($message): ?>
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    
    <div class="bg-white p-6 rounded-xl shadow mb-8 max-w-md mx-auto">

        <h3 class="text-xl font-semibold mb-4 text-center">
             Ajouter utilisateur
        </h3>

        <form method="POST" class="space-y-4">

            <input type="text" name="nom" placeholder="Nom"
                class="border p-2 rounded w-full" required>

            <input type="text" name="prenom" placeholder="Prénom"
                class="border p-2 rounded w-full" required>

            <input type="email" name="email" placeholder="Email"
                class="border p-2 rounded w-full" required>

            <input type="password" name="mot_de_passe" placeholder="Mot de passe"
                class="border p-2 rounded w-full" required>

            <select name="role" class="border p-2 rounded w-full" required>
                <option value="admin">Admin</option>
                <option value="archiviste">Archiviste</option>
                <option value="agent">Agent</option>
            </select>

            <button type="submit" name="ajouter"
                class="w-full bg-blue-500 text-white p-3 rounded hover:bg-yellow-400 transition">
                Ajouter utilisateur
            </button>

        </form>
    </div>

 
    <div class="bg-white p-4 rounded-xl shadow overflow-x-auto">

        <h3 class="text-xl font-semibold mb-4"> Liste des utilisateurs</h3>

        <table class="w-full min-w-[600px]">

            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">ID</th>
                    <th class="p-3">Nom</th>
                    <th class="p-3">Prénom</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Rôle</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3"><?= $user['id_utilisateur'] ?></td>
                        <td class="p-3"><?= htmlspecialchars($user['nom']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($user['prenom']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($user['email']) ?></td>

                        <td class="p-3">
                            <span class="px-2 py-1 bg-gray-200 rounded text-sm">
                                <?= $user['role'] ?>
                            </span>
                        </td>

                        <td class="p-3 flex gap-2">

                            <a href="modifier_utilisateur.php?id=<?= $user['id_utilisateur'] ?>"
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                ✏️ Modifier
                            </a>

                            <a href="admin.php?delete=<?= $user['id_utilisateur'] ?>"
                               onclick="return confirm('Supprimer cet utilisateur ?')"
                               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                ❌ Supprimer
                            </a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <div class="text-center mt-6">
        <a href="historique_demandes.php"
           class="text-blue-600 hover:underline font-semibold">
            📜 Voir historique des demandes
        </a>
    </div>

</div>

</body>
</html>