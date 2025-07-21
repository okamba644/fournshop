<?php
session_start();
include "connexion_base.php";

$panier = [];
$total = 0;

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    $sql = "SELECT c.id AS cart_id, p.nom, p.categorie, p.prix, p.image, c.quantity 
            FROM cart c
            JOIN produits p ON p.id = c.product_id
            WHERE c.user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($produit = $result->fetch_assoc()) {
        $panier[] = $produit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="style_panier.css">
    <style>
        .total {
            margin-top: 30px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 10px;
        }
        .sous-total {
            font-weight: bold;
            color: #009900;
        }
        .remove-btn {
            float: right;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>🛒 Mon Panier</h2>

<!-- Bouton de retour à l'accueil -->
<a href="index.php" class="btn-retour">← Retour à l'accueil</a>

<div class="panier-container">
    <?php if (empty($panier)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <?php foreach ($panier as $index => $produit): ?>
            <?php 
                $quantite = isset($produit['quantity']) && $produit['quantity'] > 0 ? intval($produit['quantity']) : 1;
                $prix = isset($produit['prix']) ? floatval($produit['prix']) : 0;
                $sous_total = $prix * $quantite;
                $total += $sous_total;
            ?>
            <div class="produit" data-prix="<?php echo $prix; ?>">
                <!-- Bouton suppression -->
                <form method="POST" class="remove-form" data-index="<?php echo $index; ?>">
                    <button type="button" class="remove-btn" title="Supprimer ce produit">❌</button>
                </form>

                <img src="img/<?php echo htmlspecialchars($produit['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($produit['nom'] ?? 'Produit'); ?>">
                <div class="infos">
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($produit['nom'] ?? ''); ?></p>
                    <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($produit['categorie'] ?? ''); ?></p>
                    <p><strong>Prix unitaire:</strong> <?php echo number_format($prix, 2); ?> dh</p>
                    <p class="sous-total"><strong>Sous-total:</strong> <span><?php echo number_format($sous_total, 2); ?></span> dh</p>
                </div>

                <!-- Formulaire de modification de quantité -->
                <form method="POST" action="modifier_quantite.php" class="quantite" data-index="<?php echo $index; ?>">
                    <label for="quantite_<?php echo $index; ?>">Quantité :</label>
                    <input type="number" name="quantite" id="quantite_<?php echo $index; ?>" value="<?php echo $quantite; ?>" min="1">
                    <button type="submit" class="btn">Modifier</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (!empty($panier)): ?>
    <div class="total" id="total-general">
        🧾 Total général : <strong><span><?php echo number_format($total, 2); ?></span> dh</strong><br>
        <a href="paiement.php" class="btn">Procéder au paiement</a>
    </div>
<?php endif; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Suppression produit -->
<script>
$('.remove-btn').on('click', function () {
    const form = $(this).closest('.remove-form');
    const index = form.data('index');

    $.post('supprimer_article.php', { index: index }, function () {
        form.closest('.produit').fadeOut(300, function() {
            $(this).remove();

            // Recalcul du total
            $.ajax({
                url: 'calcul_total.php',
                type: 'GET',
                success: function(totalResponse) {
                    $('#total-general span').text(parseFloat(totalResponse).toFixed(2));
                }
            });
        });
    });
});
</script>

<script>
$(document).ready(function() {
    $('.quantite').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const index = form.data('index');
        const quantite = parseInt(form.find('input[name="quantite"]').val());
        const produitDiv = form.closest('.produit');
        const prixUnitaire = parseFloat(produitDiv.data('prix'));

        // AJAX pour modifier dans la base
        $.ajax({
            url: 'modifier_quantite.php',
            type: 'POST',
            data: { index: index, quantite: quantite },
            success: function() {
                // 1. Mettre à jour le sous-total du produit
                const sousTotal = prixUnitaire * quantite;
                produitDiv.find('.sous-total span').text(sousTotal.toFixed(2));

                // 2. Recalculer le total général
                let total = 0;
                $('.produit').each(function() {
                    const st = parseFloat($(this).find('.sous-total span').text());
                    total += st;
                });
                $('#total-general span').text(total.toFixed(2));
            },
            error: function() {
                alert("Erreur lors de la mise à jour de la quantité.");
            }
        });
    });
});
</script>


</body>
</html>
