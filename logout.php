<?php

session_start();
session_destroy();
header("Location: index.php"); // ou la page d’accueil publique
exit;
?>

?>