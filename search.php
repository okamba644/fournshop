<?php
session_start();
include "connexion_base.php";



if (isset($_POST["recherche"])) {
    $recherche = $_POST["recherche"];
    $requette = "SELECT * FROM produits WHERE nom = ?";
    $stmt = $mysqli->prepare($requette);
    $stmt->bind_param("s", $recherche);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Fournishop</title>
    
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
								<a href="index.php" class="logo">
									<img src="./img/finallogo.png" alt="" >
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
    <div class="header-search">
        <form onsubmit="return false;">
            <select class="input-select" id="category-select" onchange="redirectToPage()">
				 <option value="store.php">All Categories</option>
               <option value="store.php"><--Filter--></option>
                <option value="Stationery.php">Stationery</option>
                <option value="Writ_corr.php">Writ. & corr</option>
                <option value="Backpack.php">Backpack</option>
                <option value="It_Multi.php">IT & Multim</option>
                <option value="math_geo.php">Math & Geo</option>
            </select>
            <input class="input" placeholder="Search here">
            <button class="search-btn">Search</button>
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
									<a href="wish_list.php">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qtyt"><?php

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($wishlistCount);
    $stmt->fetch();
    echo $wishlistCount;
    $stmt->close();
} else {
    echo 0;
}
$mysqli->close();
?>
</div>
									</a>
								</div>
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown">
									<a class="dropdown-toggle" href="panier.php"  aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty1"><?php
										include "connexion_base.php";

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($wishlistCount);
    $stmt->fetch();
    echo $wishlistCount;
    $stmt->close();
} else {
    echo 0;
}
?>
</div>
									</a>
									

								
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->


    <div class="section">
        <div class="container">
            <div class="row">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $nom = htmlspecialchars($row['nom']);
                        $categorie = htmlspecialchars($row['categorie']);
                        $prix = htmlspecialchars($row['prix']);
                        $image = htmlspecialchars($row['image']);
                    ?>
                        <div class="col-md-3 col-xs-6">
                            <div class="product">
                                <div class="product-img">
                                    <img src="img/<?= $image ?>" alt="">
                                    <div class="product-label">
                                        <span class="sale">-20%</span>
                                        <span class="new">NEW</span>
                                    </div>
                                </div>
                                <div class="product-body">
                                    <p class="product-category"><?= $categorie ?></p>
                                    <h3 class="product-name"><a href="#"><?= $nom ?></a></h3>
                                    <h4 class="product-price"><?= $prix ?> dh</h4>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="product-btns">
                                        <button class="add-to-wishlist" ">
                                            <i class="fa fa-heart-o"></i>
                                            <span class="tooltipp">add to wishlist</span>
                                        </button>
                                        <button class="quick-view">
                                            <a href="product.html">
                                                <i class="fa fa-eye"></i>
                                                <span class="tooltipp">quick view</span>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <div class="add-to-cart">
                                    <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Aucun produit disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
