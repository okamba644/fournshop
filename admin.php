<?php
// Démarre la session pour gérer la déconnexion
session_start();
include("connexion_base.php");

/// Déconnexion
if (isset($_POST['deconnexion'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['supprimer']) && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    $deleteStmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $client_id);

    if ($deleteStmt->execute()) {
        header("Location: admin.php?message=Client supprimé avec succès");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $deleteStmt->error;
    }

    $deleteStmt->close();
}






// Requête pour récupérer les clients avec infos commandes
$sql = "SELECT u.id, u.nom, u.prenom, u.email, 
               COUNT(c.id) AS nb_commandes, 
               COALESCE(SUM(c.total_commande), 0) AS total_depense
        FROM users u
        LEFT JOIN commandes c ON u.id = c.id_client
        WHERE u.role = 'client'
        GROUP BY u.id";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin</title>
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
</head>

<body>
    <div class="container mt-3">

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire déconnexion -->
        <form method="POST">
            <button type="submit" name="deconnexion" class="btn btn-danger mb-3" style="background-color: blue;">Déconnexion</button>
        </form>

        <!-- Boutons de navigation -->
        <div class="mb-4">
            <a href="#" class="btn btn-info">Afficher Clients</a>
            <a href="form_ajout.php" class="btn btn-success">Ajouter Produit</a>
            <a href="form_supp_prod.php" class="btn btn-danger">Supprimer Produit</a>
            <a href="form_update.php" class="btn btn-warning">Modifier Produit</a>
        </div>

        <!-- Tableau des clients avec commandes -->
        <h3>Liste des Clients</h3>
        <table class="table table-striped table-bordered" id="clientsTable" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Nombre commandes</th>
                    <th>Total dépensé (dh)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['nb_commandes']; ?></td>
                    <td><?php echo number_format($row['total_depense'], 2); ?></td>
                    <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de ce client ?');">
                            <input type="hidden" name="client_id" value="<?php echo $row['id']; ?>" />
                            <button type="submit" name="supprimer" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    <!-- Inclusion jQuery, Bootstrap JS et DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr_fr.json"
                }
            });
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
