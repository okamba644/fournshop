<?php
include "connexion_base.php";

if (isset($_POST['verifier'])) {
    $nom = trim($_POST['nom']);

    if (!empty($nom)) {
        $stmt = $mysqli->prepare("SELECT * FROM produits WHERE nom = ?");
        $stmt->bind_param("s", $nom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Rediriger pour afficher le formulaire de modification
            header("Location: form_update.php?produit=trouve&nom=" . urlencode($nom));
            exit;
        } else {
            $message = "Produit non trouvé.";
        }
        $stmt->close();
    } else {
        $message = "Veuillez entrer un nom de produit.";
    }

    header("Location: form_update.php?message=" . urlencode($message));
    exit;
}

if (isset($_POST['modifier'])) {
    $nom = trim($_POST['nom']);
    $prix = trim($_POST['prix']);
    $categorie = trim($_POST['categorie']);
    $image = trim($_POST['image']);

    $champs = [];
    $params = [];
    $types = "";

    if (!empty($prix)) {
        $champs[] = "prix = ?";
        $params[] = $prix;
        $types .= "s";
    }

    if (!empty($categorie)) {
        $champs[] = "cattegorie = ?";
        $params[] = $categorie;
        $types .= "s";
    }

    if (!empty($image)) {
        $champs[] = "image = ?";
        $params[] = $image;
        $types .= "s";
    }

    if (!empty($champs)) {
        $sql = "UPDATE produits SET " . implode(", ", $champs) . " WHERE nom = ?";
        $params[] = $nom;
        $types .= "s";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $message = "Produit modifié avec succès.";
        } else {
            $message = "Erreur lors de la modification : " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Aucune donnée à modifier.";
    }

    header("Location: form_update.php?message=" . urlencode($message));
    exit;
}
?>
