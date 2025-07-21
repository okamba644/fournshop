<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id'])) {
    http_response_code(401); // Non connecté
    exit;
}

$user_id = $_SESSION['id'];

// Récupère les articles du panier
$sql = "SELECT c.id AS cart_id 
        FROM cart c
        JOIN produits p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Vérifie si index fourni
if (isset($_POST['index'])) {
    $index = intval($_POST['index']);

    if (isset($cart_items[$index])) {
        $cart_id = $cart_items[$index]['cart_id'];

        $delete = $mysqli->prepare("DELETE FROM cart WHERE id = ?");
        $delete->bind_param("i", $cart_id);
        $delete->execute();

        echo "Produit supprimé.";
        exit;
    } else {
        http_response_code(400); // Mauvais index
        echo "Produit non trouvé.";
        exit;
    }
} else {
    http_response_code(400); // Paramètre manquant
    echo "Index manquant.";
    exit;
}
