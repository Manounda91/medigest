<?php
// Démarrage de la session pour vérifier si l'utilisateur est connecté
session_start();

// Inclusion de la connexion à la base de données
require_once 'db.php';

// Récupération de la liste des rendez-vous enregistrés dans la base de données
try {
    $stmt = $pdo->query("SELECT * FROM rendez_vous ORDER BY id DESC");
    $rendez_vous_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erreur = "Erreur de chargement des rendez-vous : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - MediGest</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .dashboard-container h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .top-actions {
            margin-bottom: 20px;
            text-align: right;
        }
        .btn-success {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #3498db;
            color: white;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .logout-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <!-- Header / Navigation -->
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="Logo Medigest.png" alt="Logo MediGest" class="png"> MediGest
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="login.php" class="logout-btn">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu du Dashboard -->
    <main class="dashboard-container">
        <h1>Tableau de bord - Suivi des rendez-vous</h1>
        <p>Bienvenue dans votre espace de gestion. Voici la liste des rendez-vous enregistrés dans le système :</p>

        <?php if (isset($erreur)): ?>
            <p style="color: red;"><?php echo $erreur; ?></p>
        <?php endif; ?>

        <!-- Bouton pour ajouter un rendez-vous bien placé -->
        <div class="top-actions">
            <a href="rendez_vous.php" class="btn-success">+ Prendre un nouveau rendez-vous</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du patient</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Motif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rendez_vous_list)): ?>
                    <?php foreach ($rendez_vous_list as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars(($row['nom_patient'] ?? '') . ' ' . ($row['prenom_patient'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($row['date_rdv']); ?></td>
                        <td><?php echo htmlspecialchars($row['heure_rdv']); ?></td>
                        <td><?php echo htmlspecialchars($row['motif']); ?></td>
                        <td>
                            <a href="supprimer_rdv.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');" 
                               style="padding: 6px 12px; background-color: #e74c3c; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                               Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666;">Aucun rendez-vous trouvé pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

</body>
</html>