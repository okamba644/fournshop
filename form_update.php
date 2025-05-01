<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un produit</title>
    <style>
        /* Style global */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Conteneur principal */
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: scale(1.05);
        }

        /* Titre */
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #d32f2f;
            text-transform: uppercase;
            animation: fadeIn 2s;
        }

        /* Champs de saisie */
        .champ input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #d32f2f;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .champ input:focus {
            border-color: #c62828;
        }

        /* Bouton */
        button[type="submit"] {
            background-color: #d32f2f;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #c62828;
            transform: scale(1.05);
        }

        /* Animation fade-in pour le titre */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Message d'erreur/succès */
        p {
            font-size: 18px;
            color: #d32f2f;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Modifier un produit</h2>

    <!-- Formulaire de vérification du produit -->
    <form method="post" action="update.php">
        <div class="champ">
            <label for="nom">Nom du produit :</label>
            <input type="text" name="nom" id="nom" required>
            <button type="submit" name="verifier">Vérifier</button>
        </div>
    </form>

    <?php
    // Si retour avec indication que le produit existe, on affiche les champs de modification
    if (isset($_GET["produit"]) && $_GET["produit"] === "trouve" && isset($_GET["nom"])) {
        $nom = htmlspecialchars($_GET["nom"]);
    ?>
    <form method="post" action="update.php">
        <input type="hidden" name="nom" value="<?= $nom ?>">
        <div class="champ">
            <label for="prix">Prix :</label>
            <input type="text" name="prix" id="prix">
        </div>
        <div class="champ">
            <label for="categorie">Catégorie :</label>
            <input type="text" name="categorie" id="categorie">
        </div>
        <div class="champ">
            <label for="image">Image :</label>
            <input type="file" name="image" id="image">
        </div>
        <button type="submit" name="modifier">Modifier</button>
    </form>
    <?php
    }

    // Message en cas d’erreur ou succès
    if (isset($_GET["message"])) {
        echo "<p><strong>" . htmlspecialchars($_GET["message"]) . "</strong></p>";
    }
    ?>
</div>

</body>
</html>
