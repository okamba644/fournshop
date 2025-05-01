<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un produit</title>
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

        /* Conteneur du formulaire */
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
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #d32f2f;
            text-transform: uppercase;
            animation: fadeIn 2s;
        }

        /* Champ de saisie */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #d32f2f;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
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
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #c62828;
            transform: scale(1.05);
        }

        /* Animation de texte */
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

        /* Animation de bouton */
        @keyframes buttonHover {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.1);
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h1>Supprimer un produit</h1>
        <form method="post" action="supprimer_produit.php">
            <label for="nom">Nom du produit à supprimer :</label>
            <input type="text" name="nom" id="nom" required>
            <button type="submit" name="supprimer">Supprimer</button>
        </form>
    </div>
</body>
</html>
