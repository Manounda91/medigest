<?php
session_start();
require_once 'db.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
        // Vérification si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            // Hashage sécurisé du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            if ($insert->execute([$nom, $prenom, $email, $hashed_password])) {
                header('Location: login.php');
                exit();
            } else {
                $erreur = "Erreur lors de l'inscription.";
            }
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - MediGest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Créer un compte - MediGest</h2>

        <?php if (!empty($erreur)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $erreur; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label>Nom :</label>
            <input type="text" name="nom" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">

            <label>Prénom :</label>
            <input type="text" name="prenom" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">

            <label>Email :</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">

            <label>Mot de passe :</label>
            <div style="display: flex; align-items: center; position: relative; width: 100%; margin-bottom: 15px;">
                <!-- Champ input -->
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; padding-right: 45px; box-sizing: border-box; margin: 0;">
                
                <!-- Icône placée dans le flux flex, calée à l'intérieur à droite -->
                <span onclick="togglePassword()" style="position: absolute; right: 12px; cursor: pointer; user-select: none; font-size: 16px;">👁️‍🗨️</span>
            </div>
            
            <button type="submit" style="width: 100%; padding: 10px; background-color: #2ecc71; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">S'inscrire</button>
        </form>

        <!-- Lien inversé pour les utilisateurs qui ont déjà un compte -->
        <p style="text-align: center; margin-top: 20px;">
            Déjà un compte ? <a href="login.php" style="color: #3498db; text-decoration: none; font-weight: bold;">Se connecter</a>
        </p>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
    </script>
</body>
</html>