<?php
include "connexio_base.php";
// Traitement de la suppression d'un client
if (isset($_POST['supprimer']) && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    // Requête pour supprimer le client
    $deleteStmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $client_id);
    
    if ($deleteStmt->execute()) {
        // Suppression réussie, redirection ou message
        header("Location: dashboard_admin.php?message=Client supprimé avec succès");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $deleteStmt->error;
    }

    $deleteStmt->close();
}
?>
