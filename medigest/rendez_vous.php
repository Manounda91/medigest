<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_patient = trim($_POST['nom_patient']);
    $prenom_patient = trim($_POST['prenom_patient']);
    $date_rdv = $_POST['date_rdv'];
    $heure_rdv = $_POST['heure_rdv'];
    $motif = trim($_POST['motif']);
    $utilisateur_id = $_SESSION['user_id'];

    if (!empty($nom_patient) && !empty($prenom_patient) && !empty($date_rdv) && !empty($heure_rdv) && !empty($motif)) {
        $stmt = $pdo->prepare("INSERT INTO rendez_vous (utilisateur_id, nom_patient, prenom_patient, date_rdv, heure_rdv, motif) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$utilisateur_id, $nom_patient, $prenom_patient, $date_rdv, $heure_rdv, $motif])) {
            header('Location: dashboard.php');
            exit();
        } else {
            $message = "Erreur lors de l'enregistrement du rendez-vous.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre un rendez-vous - MediGest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Prendre un rendez-vous</h2>
        
        <?php if (!empty($message)): ?>
            <p class="msg" style="color: red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="rendez_vous.php" method="POST">
            <label>Nom du patient :</label>
            <input type="text" name="nom_patient" required>

            <label>Prénom du patient :</label>
            <input type="text" name="prenom_patient" required>

            <label>Date :</label>
            <input type="date" name="date_rdv" required>
            
            <label>Heure :</label>
            <input type="time" name="heure_rdv" required>

            <label>Motif :</label>
            <input type="text" name="motif" required>
            
            <button type="submit">Confirmer le rendez-vous</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            <a href="dashboard.php">Retour au tableau de bord</a>
        </p>
    </div>
</body>
</html>