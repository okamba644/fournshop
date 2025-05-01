<?php
// Démarre la session pour gérer la déconnexion
session_start();
include("connexion_base.php");


// Déconnexion
if (isset($_POST['deconnexion'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Requête pour récupérer les clients
$sql = "SELECT id, nom, prenom, email FROM users WHERE role = 'client'";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();


// Traitement de la suppression d'un client
if (isset($_POST['supprimer']) && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    // Requête pour supprimer le client
    $deleteStmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $client_id);
    
    if ($deleteStmt->execute()) {
        // Suppression réussie, redirection ou message
        header("Location: admin.php?message=Client supprimé avec succès");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $deleteStmt->error;
    }

    $deleteStmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php
if (isset($_GET['message'])) {
    echo "<div class='alert alert-success' role='alert'>" . htmlspecialchars($_GET['message']) . "</div>";
}
?>

<body>
    <!-- Menu en haut avec bouton de déconnexion -->
    <div class="container mt-3">
        <form method="POST">
            <button type="submit" name="deconnexion" class="btn btn-danger" style="position: absolute; top: 20px; left: 20px; background-color: blue;">Déconnexion</button>
        </form>

        <!-- Les autres boutons -->
        <div class="mt-5">
            <a href="#" class="btn btn-info">Afficher Clients</a>
            <a href="form_ajout.php" class="btn btn-success">Ajouter Produit</a>
            <a href="form_supp_prod.php" class="btn btn-danger">Supprimer Produit</a>
            <a href="form_update.php" class="btn btn-warning">Modifier Produit</a>
        </div>

        <!-- Tableau dynamique des clients -->
        <div class="mt-5">
            <h3>Liste des Clients</h3>
            <table class="table table-striped table-bordered" id="clientsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                     
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nom']; ?></td>
            <td><?php echo $row['prenom']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <!-- Bouton de suppression -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="client_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="supprimer" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

            </table>
        </div>
    </div>

    <!-- Inclusion de jQuery et Bootstrap JS pour rendre le tableau réactif -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialisation du tableau avec DataTables pour interactivité -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable();
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
