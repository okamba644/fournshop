<?php
session_start();
include("connexion_base.php");

if (!isset($_SESSION['id'])) {
    echo "non_connecte";
    exit;
}

if (isset($_POST['product_id'])) {
    $user_id = $_SESSION['id'];
    $product_id = intval($_POST['product_id']);
    $date_ajout = date("Y-m-d H:i:s");

    $check_sql = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $mysqli->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO wishlist (user_id, product_id, date_ajout) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insert_sql);
        $stmt->bind_param("iis", $user_id, $product_id, $date_ajout);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "exists";
    }
}
?>
