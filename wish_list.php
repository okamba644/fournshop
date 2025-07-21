<?php
session_start();
require_once 'connexion_base.php'; // Assure-toi que ce fichier contient ta connexion mysqli

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Suppression d'un produit de la wishlist
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $mysqli->prepare($delete_sql);
    $stmt->bind_param("ii", $user_id, $delete_id);
    $stmt->execute();
}

// Récupération des produits de la wishlist
$wishlist = [];
$sql = "SELECT p.id, p.nom, p.categorie, p.prix, p.image
        FROM wishlist w
        JOIN produits p ON p.id = w.product_id
        WHERE w.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $wishlist[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Wishlist</title>
    <link rel="stylesheet" href="style_wish.css">
</head>
<body>

<h2>❤ Ma Liste de Souhaits</h2>
<a href="index.php" class="btn-retour">← Retour à l'accueil</a>

<div class="wishlist-container">
    <?php if (empty($wishlist)): ?>
        <p style="text-align:center; grid-column: 1/-1;">Votre liste de souhaits est vide.</p>
    <?php else: ?>
        <?php foreach ($wishlist as $produit): ?>
            <div class="wishlist-item">
                <img src="img/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($produit['nom']); ?></p>
                <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($produit['categorie']); ?></p>
                <p><strong>Prix :</strong> <?php echo number_format($produit['prix'], 2); ?> dh</p>
                <form class="remove-form" method="POST" action="">
                    <input type="hidden" name="delete_id" value="<?php echo $produit['id']; ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
