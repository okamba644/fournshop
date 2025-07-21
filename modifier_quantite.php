<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id'])) {
    http_response_code(401); // Non autorisé
    exit;
}

$user_id = $_SESSION['id'];

// On récupère tous les produits du panier pour cet utilisateur
$sql = "SELECT c.id AS cart_id 
        FROM cart c
        JOIN produits p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Vérifie si l’index est valide
if (isset($_POST['index']) && isset($_POST['quantite'])) {
    $index = intval($_POST['index']);
    $quantite = intval($_POST['quantite']);

    if ($quantite < 1) $quantite = 1;

    if (isset($cart_items[$index])) {
        $cart_id = $cart_items[$index]['cart_id'];

        $update = $mysqli->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $quantite, $cart_id);
        $update->execute();

        echo "Quantité mise à jour.";
        exit;
    } else {
        http_response_code(400); // Index invalide
        echo "Produit introuvable.";
        exit;
    }
} else {
    http_response_code(400); // Requête invalide
    echo "Données manquantes.";
    exit;
}
