<?php
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnees = "e_commerce";

// Connexion
$mysqli = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérification
if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}
?>
