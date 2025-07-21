<?php
session_start();
include("connexion_base.php"); // ta connexion à la base MySQLi

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Vous devez vous connecter pour ajouter au panier.');</script>";
    exit;
}

if (isset($_POST['ajout'])) {
    // Validation et sécurisation
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
    $categorie = isset($_POST['categorie']) ? htmlspecialchars($_POST['categorie']) : '';
    $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
    $image = isset($_POST['image']) ? htmlspecialchars($_POST['image']) : '';
  $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1; // valeur par défaut 1


    if ($product_id <= 0) {
        echo "<script>alert('Produit invalide.');</script>";
        exit;
    }

    // Gestion du panier en session
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Vérifier si le produit est déjà dans le panier en session
    $indexProduit = null;
    foreach ($_SESSION['panier'] as $index => $item) {
        if ($item['id'] == $product_id) {
            $indexProduit = $index;
            break;
        }
    }

    if ($indexProduit !== null) {
        // Le produit est déjà dans le panier : on incrémente la quantité
        $_SESSION['panier'][$indexProduit]['quantite'] += $quantite;
    } else {
        // Sinon on ajoute le produit complet avec quantité
        $_SESSION['panier'][] = [
            'id' => $product_id,
            'nom' => $nom,
            'categorie' => $categorie,
            'prix' => $prix,
            'image' => $image,
            'quantite' => $quantite
        ];
    }

    // Gestion du panier en base de données
    $user_id = $_SESSION['id'];
    $date_ajout = date("Y-m-d H:i:s");

    // Vérifier si le produit est déjà dans la base
    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $mysqli->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insertion nouvelle ligne panier
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity, date_ajout) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_sql);
        $stmt->bind_param("iiis", $user_id, $product_id, $quantite, $date_ajout);
        if ($stmt->execute()) {
            echo "<script>alert('Produit ajouté au panier.');</script>";
        header("location:it_Multi.php");
          echo "<script>alert('Produit ajouté au panier.');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'ajout au panier.');</script>";
        }
    } 
}
?>
