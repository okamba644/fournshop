<?php
include("connexion_base.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["connexion"])) {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo "<script>alert('Veuillez entrer un email'); window.history.back();</script>";
        exit;
    }

    // On récupère toutes les infos nécessaires (y compris l'id de l'utilisateur)
    $sql = "SELECT id, role, nom, prenom FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($data = $result->fetch_assoc()) {
        // Stockage dans la session
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nom'] = $data['nom'];
        $_SESSION['prenom'] = $data['prenom'];

        $userId = $data['id'];

        

        // Redirection selon le rôle
        if ($data['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php"); // page d’accueil utilisateur
        }
        exit;

    } else {
        echo "<script>alert('Email introuvable !'); window.history.back();</script>";
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "Accès non autorisé.";
}
?>
