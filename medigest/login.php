<?php
session_start();
require_once 'db.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'] ?? '';
            header('Location: dashboard.php');
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
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
    <title>Connexion - MediGest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion à MediGest</h2>

        <?php if (!empty($erreur)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $erreur; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label>Email :</label>
            <input type="email" name="email" required>

        <label>Mot de passe :</label>
            <div style="display: flex; align-items: center; position: relative; width: 100%; margin-bottom: 15px;">
                <!-- Champ input -->
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; padding-right: 45px; box-sizing: border-box; margin: 0;">
                
                <!-- Icône placée dans le flux flex, calée à l'intérieur à droite -->
                <span onclick="togglePassword()" style="position: absolute; right: 12px; cursor: pointer; user-select: none; font-size: 16px;">👁️‍🗨️</span>
            </div>
            
            <button type="submit" style="width: 100%; padding: 10px; background-color: #3498db; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Se connecter</button>
        </form>

        <!-- Lien pour les utilisateurs qui n'ont pas encore de compte -->
        <p style="text-align: center; margin-top: 20px;">
            Pas encore de compte ? <a href="register.php" style="color: #3498db; text-decoration: none; font-weight: bold;">Créer un compte</a>
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