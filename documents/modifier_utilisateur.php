<?php
session_start();
require_once("../config/connexion.php");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Utilisateur introuvable");
}


$stmt = $connexion->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("❌ Utilisateur inexistant");
}

$message = "";

if (isset($_POST['modifier'])) {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mot_de_passe = $_POST['mot_de_passe'];

  
    $check = $connexion->prepare("
        SELECT * FROM utilisateurs 
        WHERE email = ? AND id_utilisateur != ?
    ");
    $check->execute([$email, $id]);

    if ($check->rowCount() > 0) {

        $message = "❌ Email déjà utilisé";

    } else {

        try {

            if (!empty($mot_de_passe)) {

                $sql = $connexion->prepare("
                    UPDATE utilisateurs 
                    SET nom=?, prenom=?, email=?, role=?, mot_de_passe=? 
                    WHERE id_utilisateur=?
                ");

                $result = $sql->execute([
                    $nom,
                    $prenom,
                    $email,
                    $role,
                    $mot_de_passe,
                    $id
                ]);

            } else {

                $sql = $connexion->prepare("
                    UPDATE utilisateurs 
                    SET nom=?, prenom=?, email=?, role=? 
                    WHERE id_utilisateur=?
                ");

                $result = $sql->execute([
                    $nom,
                    $prenom,
                    $email,
                    $role,
                    $id
                ]);
            }

       
            if ($result) {
                echo "<script>
                    alert('✅ Utilisateur modifié avec succès');
                    window.location.href='admin.php';
                </script>";
                exit();
            } else {
                $message = "❌ Utilisateur non modifié";
            }

        } catch (Exception $e) {
            $message = "❌ Erreur lors de la modification";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
</head>
<body>

<h2>✏️ Modifier utilisateur</h2>

<p style="color:red;"><?= $message ?></p>

<form method="POST">

    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br><br>

    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required><br><br>

    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

    <select name="role" required>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="archiviste" <?= $user['role']=='archiviste'?'selected':'' ?>>Archiviste</option>
        <option value="agent" <?= $user['role']=='agent'?'selected':'' ?>>Agent</option>
    </select><br><br>

    <input type="password" name="mot_de_passe" placeholder="Nouveau mot de passe (laisser vide si inchangé)"><br><br>

    <button type="submit" name="modifier">Modifier</button>

</form>

<br>

<a href="admin.php">⬅ Retour</a>

</body>
</html>