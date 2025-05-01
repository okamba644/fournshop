<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion & Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="form_connexion.css">
</head>
<body>

<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
  <div class="auth-container d-flex shadow-lg rounded-4 overflow-hidden">
    
    <!-- Partie gauche : Connexion -->
    <div class="left-panel d-flex flex-column justify-content-center align-items-center text-white p-5">
      <h2 class="mb-3">Welcome Back!</h2>
      <p class="text-center">To keep connected with us please login<br>with your personal info</p>
      <a href="formulaire_inscription.php" class="btn btn-outline-light mt-3">SIGN IN</a>
    </div>

    <!-- Partie droite : Création de compte -->
    <div class="right-panel bg-white p-5">
      <h3 class="text-center mb-4">Create Account</h3>
      <div class="d-flex justify-content-center gap-3 mb-3">
        <button class="btn btn-light border"><i class="bi bi-facebook"></i></button>
        <button class="btn btn-light border"><i class="bi bi-google"></i></button>
        <button class="btn btn-light border"><i class="bi bi-linkedin"></i></button>
      </div>
      <p class="text-center text-muted mb-4">or use your email for registration</p>
      <form action="connexion.php" method="POST">
      
        <div class="mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <input type="submit" class="btn btn-danger w-100" name="connexion" value="SIGN UP"></input>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap + icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
