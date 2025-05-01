<?php
include("connexion_base.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["connexion"])) {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo "<script>alert('Veuillez entrer un email'); window.history.back();</script>";
        exit;
    }

    // Récupère aussi le nom et prénom
    $sql = "SELECT role, nom, prenom FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($data = $result->fetch_assoc()) {
        $_SESSION['role'] = $data['role'];
        $_SESSION['nom'] = $data['nom'];
        $_SESSION['prenom'] = $data['prenom'];

        if ($data['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php"); // page d’accueil utilisateur
        }
    } else {
        echo "<script>alert('Email introuvable !'); window.history.back();</script>";
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "Accès non autorisé.";
}
?>
