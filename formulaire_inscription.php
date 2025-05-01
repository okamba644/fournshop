<?php
// Initialisation des variables
$nom = $prenom = $email = $adresse = $tel =$pwd= "";
$nomerr = $prenomerr = $emailerr = $adrerr = $telerr =$pwderr= "";

// Vérification du formulaire
if (isset($_POST["s'inscrire"])) {

    // Validation du nom
    if (empty($_POST["nom"])) {
        $nomerr = "Veuillez remplir le champ";
    } else {
        $nom = $_POST["nom"];
        // Validation du format du nom
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nom)) {
            $nomerr = "Le format du nom n'est pas valide";
        }
    }

    // Validation du prénom
    if (empty($_POST["prenom"])) {
        $prenomerr = "Veuillez remplir le champ";
    } else {
        $prenom = $_POST["prenom"];
        // Validation du format du prénom
        if (!preg_match("/^[a-zA-Z-' ]*$/", $prenom)) {
            $prenomerr = "Le format du prénom n'est pas valide";
        }
    }

    // Validation du numéro de téléphone
    if (empty($_POST["tel"])) {
        $telerr = "Veuillez remplir le champ";
    } else {
        $tel = $_POST["tel"];
        // Validation du format du téléphone
        if (!preg_match("/^0[1-9](?:[ .-]?\d{2}){4}$/", $tel)) {
            $telerr = "Le format du numéro de téléphone n'est pas valide";
        }
    }

    // Validation de l'email
    if (empty($_POST["email"])) {
        $emailerr = "Veuillez remplir le champ";
    } else {
        $email = $_POST["email"];
        // Validation du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailerr = "L'email n'est pas valide";
        }
    }

    // Validation de l'adresse
    if (empty($_POST["adresse"])) {
        $adrerr = "Veuillez remplir le champ";
    } else {
        $adresse = $_POST["adresse"];
    }
    if (empty($_POST["password"])) {
        $pwderr = "Veuillez remplir le champ";
    } else {
        $pwd = $_POST["password"];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulaire d'inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="formulaire_inscri.css">
</head>
<body>

<div class="form-container">
    <h2 class="form-title"><i class="bi bi-person-fill icon"></i>Inscription</h2>
    <form method="POST" action="inscription.php">

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" value="<?php echo $nom; ?>">
            <span class="erreur"><?php echo $nomerr; ?></span>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" value="<?php echo $prenom; ?>">
            <span class="erreur"><?php echo $prenomerr; ?></span>
        </div>

        <div class="mb-3">
            <label for="tel" class="form-label">Numéro de téléphone</label>
            <input type="tel" class="form-control" id="tel" name="tel" placeholder="06 12 34 56 78" value="<?php echo $tel; ?>">
            <span class="erreur"><?php echo $telerr; ?></span>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="exemple@email.com" value="<?php echo $email; ?>">
            <span class="erreur"><?php echo $emailerr; ?></span>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="adresse" name="password" placeholder="mot de pass" value="<?php echo $adresse; ?>">
            <span class="erreur"><?php echo $pwderr; ?></span>
        </div>

        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Votre adresse complète" value="<?php echo $adresse; ?>">
            <span class="erreur"><?php echo $adrerr; ?></span>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-custom btn-lg" name="s'inscrire">S'inscrire</button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
