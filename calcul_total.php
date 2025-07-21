<?php
session_start();
include "connexion_base.php";

$total = 0;

if (!isset($_SESSION['id'])) {
    echo number_format($total, 2); // Renvoie 0.00 si non connecté
    exit;
}

$user_id = $_SESSION['id'];

$sql = "SELECT p.prix, c.quantity 
        FROM cart c
        JOIN produits p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $prix = floatval($row['prix']);
    $quantite = intval($row['quantity']);
    $total += $prix * $quantite;
}

echo number_format($total, 2);
