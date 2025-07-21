<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Récupérer le panier (depuis la table cart)
$sql = "SELECT c.product_id, p.nom, p.prix, p.image, c.quantity 
        FROM cart c
        JOIN produits p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$panier = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $panier[] = $row;
    $total += $row['prix'] * $row['quantity'];
}

// Si formulaire soumis pour valider la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    $mode_paiement = $_POST['mode_paiement'] ?? '';

    if (empty($panier)) {
        $message = "Votre panier est vide, impossible de passer la commande.";
    } elseif (empty($mode_paiement)) {
        $message = "Veuillez choisir un mode de paiement.";
    } else {
        // 1. Insérer dans commandes
        $sqlCmd = "INSERT INTO commandes (id_client, mode_paiement, total_commande) VALUES (?, ?, ?)";
        $stmtCmd = $mysqli->prepare($sqlCmd);
        $stmtCmd->bind_param("isd", $user_id, $mode_paiement, $total);
        $stmtCmd->execute();

        $id_commande = $stmtCmd->insert_id;

        // 2. Insérer chaque ligne commande
        $sqlLigne = "INSERT INTO lignes_commandes (id_commande, id_produit, quantite, prix_unitaire, total_ligne) VALUES (?, ?, ?, ?, ?)";
        $stmtLigne = $mysqli->prepare($sqlLigne);

        foreach ($panier as $item) {
            $quantite = $item['quantity'];
            $prix_unitaire = $item['prix'];
            $total_ligne = $quantite * $prix_unitaire;

            $stmtLigne->bind_param("iiidd", $id_commande, $item['product_id'], $quantite, $prix_unitaire, $total_ligne);
            $stmtLigne->execute();
        }

        // 3. Vider panier
        $sqlVider = "DELETE FROM cart WHERE user_id = ?";
        $stmtVider = $mysqli->prepare($sqlVider);
        $stmtVider->bind_param("i", $user_id);
        $stmtVider->execute();

        $message = "Merci, votre commande a été enregistrée avec succès !";
        // On peut rediriger ou afficher message
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Validation de la commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #111; 
            color: #fff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container { 
            margin-top: 50px; 
            max-width: 900px;
        }
        h2 {
            color: #c00;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        table {
            background: #222;
            border-radius: 8px;
            overflow: hidden;
        }
        thead tr {
            background-color: #c00;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
        }
        tbody tr:hover {
            background-color: #330000;
        }
        td, th {
            vertical-align: middle !important;
            text-align: center;
            padding: 12px;
        }
        img {
            max-width: 80px;
            border-radius: 6px;
            box-shadow: 0 0 8px #c00;
        }
        .total {
            font-weight: 700;
            font-size: 1.5rem;
            margin-top: 25px;
            color: #c00;
            text-align: right;
            text-shadow: 0 0 6px #900;
        }
        .mode-paiement {
            margin-top: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
            color: #eee;
            font-size: 1.1rem;
        }
        select.form-select {
            max-width: 300px;
            margin: 10px auto 20px auto;
            background-color: #222;
            color: #fff;
            border: 2px solid #c00;
            border-radius: 8px;
            font-weight: 600;
        }
        select.form-select:focus {
            outline: none;
            box-shadow: 0 0 8px #c00;
            border-color: #f00;
        }
        .btn-primary {
            background-color: #c00;
            border: none;
            padding: 12px 28px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 12px #900;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #900;
            box-shadow: 0 0 14px #f00;
        }
        .btn-secondary {
            background-color: #444;
            color: #ddd;
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 8px;
            margin-left: 15px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #666;
            color: #fff;
        }
        .alert {
            background-color: #330000;
            border: 1px solid #c00;
            color: #f88;
            font-weight: 600;
            text-align: center;
            margin-top: 20px;
            border-radius: 8px;
            padding: 15px;
            text-shadow: 0 0 4px #f00;
        }
        p {
            font-size: 1.1rem;
            margin-top: 25px;
            text-align: center;
        }
        a.btn-light {
            display: inline-block;
            margin-top: 15px;
            color: #c00;
            border: 2px solid #c00;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        a.btn-light:hover {
            background-color: #c00;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Récapitulatif de votre commande</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (empty($panier)): ?>
        <p>Votre panier est vide.</p>
        <a href="index.php" class="btn btn-light mt-3">Retour à l'accueil</a>
    <?php else: ?>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($panier as $item): ?>
                <tr>
                    <td><img src="img/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['nom']); ?>"></td>
                    <td><?php echo htmlspecialchars($item['nom']); ?></td>
                    <td><?php echo number_format($item['prix'], 2); ?> dh</td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td><?php echo number_format($item['prix'] * $item['quantity'], 2); ?> dh</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">Total à payer : <?php echo number_format($total, 2); ?> dh</div>

        <form method="POST" class="mode-paiement">
            <label for="mode_paiement" class="form-label">Choisissez votre mode de paiement :</label>
            <select name="mode_paiement" id="mode_paiement" class="form-select" required>
                <option value="">-- Sélectionnez --</option>
                <option value="Carte bancaire">Carte bancaire</option>
                <option value="Paypal">Paypal</option>
                <option value="Paiement à la livraison">Paiement à la livraison</option>
            </select>

            <button type="submit" name="valider" class="btn btn-primary mt-3">Confirmer la commande</button>
            <a href="index.php" class="btn btn-secondary mt-3 ms-2">Poursuivre mes achats</a>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
