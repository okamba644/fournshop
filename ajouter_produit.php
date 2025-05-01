<?php
include("connexion_base.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $categorie = $_POST["categorie"];
    $prix = $_POST["prix"];
    
    // Gestion de l’image
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $chemin_image = "img/" . basename($image);
    
    if (move_uploaded_file($image_tmp, $chemin_image)) {
        $sql = "INSERT INTO produits (nom, categorie, prix, image) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssds", $nom, $categorie, $prix, $image);
        $stmt->execute();
        echo "<script>alert('Produit ajouté !'); window.location.href='admin.php';</script>";
    } else {
        echo "Erreur lors de l'upload de l'image.";}
}
?>