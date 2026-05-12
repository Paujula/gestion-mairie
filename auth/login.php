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
        $message = "❌ Identifiants incorrects";
    }
}
?>

<?php include("../includes/header.php"); ?>

<h2>Connexion</h2>

<p style="color:red;"><?= $message ?></p>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br><br>
    <button>Se connecter</button>
</form>