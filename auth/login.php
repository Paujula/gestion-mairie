<?php
session_start();
require_once("../config/connexion.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connexion->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['mot_de_passe']) {

        $_SESSION['user'] = $user;

        if (isset($_SESSION['redirect_doc'])) {
            $id = $_SESSION['redirect_doc'];
            unset($_SESSION['redirect_doc']);
            header("Location: ../documents/consulter.php?id=" . $id);
            exit();
        }

        header("Location: ../documents/ajout_document.php");
        exit();

    } else {
        $message = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

    
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
             Connexion
        </h2>

        <?php if ($message): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                Se connecter
            </button>

        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            © Gestion des archives
        </p>

    </div>

</body>
</html>