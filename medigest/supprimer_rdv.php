<?php
session_start();
require_once 'db.php';

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Suppression du rendez-vous si l'ID est présent
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_rdv = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM rendez_vous WHERE id = ?");
        $stmt->execute([$id_rdv]);
    } catch (PDOException $e) {
        // Gestion silencieuse de l'erreur
    }
}

// Retour au tableau de bord
header('Location: dashboard.php');
exit();
?>