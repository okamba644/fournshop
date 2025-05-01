<?php
include "connexion_base.php";  // Inclusion de la connexion à la base de données

if (isset($_POST["s'inscrire"])) {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $adresse = $_POST["adresse"];

    // Hachage du mot de passe pour la sécurité
    

    // Requête SQL pour insérer les données dans la base de données
    $sql = "INSERT INTO users (nom, prenom, tel, email,password ,adresse ) 
            VALUES ('$nom', '$prenom', '$tel', '$email', '$password', '$adresse')";
    
    // Exécution de la requête
    $reponse = $mysqli->query($sql);

    if ($reponse === false) {
        die("Erreur MySQL : " . $mysqli->error); // Afficher l'erreur MySQL si la requête échoue
    } else {
        // Si l'insertion réussit, afficher un message de succès et rediriger après 3 secondes
        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Inscription réussie</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
            <style>
                body {
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .success-container {
                    background-color: white;
                    border: 2px solid #ff0000;
                    border-radius: 10px;
                    padding: 20px;
                    text-align: center;
                    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                    animation: fadeIn 1s ease-in-out;
                }
                .success-container h1 {
                    color: #ff0000;
                    font-size: 24px;
                }
                .icon-check {
                    font-size: 50px;
                    color: #28a745;
                }
                .redirect-message {
                    color: #000;
                    margin-top: 15px;
                    font-size: 18px;
                    animation: fadeIn 1s ease-in-out;
                }
                .btn {
                    margin-top: 20px;
                    background-color: #ff0000;
                    color: white;
                    border: none;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }
            </style>
        </head>
        <body>
            <div class="success-container">
                <i class="bi bi-check-circle icon-check"></i>
                <h1>Inscription réussie !</h1>
                <div class="redirect-message">
                    Bienvenue à Fournshop, Mr ' . $nom . ' ' . $prenom . ' !
                </div>
                <div>
                    <button class="btn" disabled>Redirection dans 3 secondes...</button>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "index.php";
                }, 3000);
            </script>
        </body>
        </html>';
    }
}
?>
