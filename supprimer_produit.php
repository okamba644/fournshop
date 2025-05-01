<?php
include "connexion_base.php";

if (isset($_POST["supprimer"])) {
    if (!empty($_POST["nom"])) {
        $nom_produit = trim($_POST["nom"]);

        // Vérifier si le produit existe
        $checkStmt = $mysqli->prepare("SELECT * FROM produits WHERE nom = ?");
        $checkStmt->bind_param("s", $nom_produit);
        $checkStmt->execute();
        $resultCheck = $checkStmt->get_result();

        if ($resultCheck->num_rows > 0) {
            // Supprimer le produit
            $deleteStmt = $mysqli->prepare("DELETE FROM produits WHERE nom = ?");
            $deleteStmt->bind_param("s", $nom_produit);

            if ($deleteStmt->execute()) {
                echo "<script>alert('Le produit \"$nom_produit\" a été supprimé avec succès.');</script>";
            } else {
                echo "<script>alert('Erreur lors de la suppression : " . addslashes($deleteStmt->error) . "');</script>";
            }

            $deleteStmt->close();
        } else {
            echo "<script>alert('Aucun produit nommé \"$nom_produit\" n\\'a été trouvé.');</script>";
        }

        $checkStmt->close();
    } else {
        echo "<script>alert('Veuillez entrer un nom de produit.');</script>";
    }

    $mysqli->close();
}
?>
