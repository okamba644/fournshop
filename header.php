<?php
include "connexion_base.php";
session_start();




?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Fournishop</title>
		<link rel="shortcut icon" href="img/finallogo.png" type="image/x-icon">
		<script src="script.js"></script>
		
		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="css/font-awesome.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="css/style.css"/>
		

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +212 706405308</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> okamba42@gmail.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> Rabat Maroc</a></li>
					</ul>
					<ul class="header-links pull-right">
						
					<?php if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])): ?>
		<li class="user-dropdown">
			<a href="#">
				<i class="fa fa-user-o"></i> <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?> <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu-user">
				<li><a href="logout.php">Déconnexion</a></li>
			</ul>
		</li>
	

	<?php else: ?>
		<li><a href="form_connexion.php"><i class="fa fa-user-o"></i> Mon Compte</a></li>
	<?php endif; ?>
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="./img/finallogo.png" alt="" >
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
    <div class="header-search">
        <form action="search.php" method="post">
            <select class="input-select" id="category-select" onchange="redirectToPage()">
				
                <option value="store.php">All Categories</option>
				<option value="store.php"><--Filter--></option>
                <option value="Stationery.php">Stationery</option>
                <option value="">Writ. & corr</option>
                <option value="">Backpack</option>
                <option value="It_Multi.php">IT & Multim</option>
                <option value="">Math & Geo</option>
            </select>
            <input class="input" placeholder="Search here" name="recherche">
            <button class="search-btn" name="search">Search</button>
			


        </form>
    </div>
</div>

<script>
function redirectToPage() {
    const select = document.getElementById("category-select");
    const selectedValue = select.value;
    if (selectedValue) {
        window.location.href = selectedValue;
    }
}
</script>

						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
								<div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty"></div>
									</a>
								</div>
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
    <i class="fa fa-shopping-cart"></i>
    <span>Your Cart</span>
    <div class="qty-1"></div> <!-- Le compteur s'affiche ici -->
  </a>
</div>
<?php
include "panier.php"



?>