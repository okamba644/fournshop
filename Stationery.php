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
									<a href="wish_list.php">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty1"><?php

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
?></div>
									</a>
								</div>
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="panier.php" aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty1">0</div>
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
			<?php
			include("connexion_base.php");

			$sql = "SELECT * FROM produits  where categorie='Stationery' ORDER BY id DESC";
			$result = $mysqli->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$id = htmlspecialchars($row['id']);
					$nom = htmlspecialchars($row['nom']);
					$categorie = htmlspecialchars($row['categorie']);
					$prix = htmlspecialchars($row['prix']);
					$image = htmlspecialchars($row['image']);

					echo '
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="img/' . $image . '" alt="">
								<div class="product-label">
									<span class="sale">-20%</span>
									<span class="new">NEW</span>
								</div>
							</div>
							<div class="product-body">
								<p class="product-category">' . $categorie . '</p>
								<h3 class="product-name"><a href="#">' . $nom . '</a></h3>
								<h4 class="product-price">' . $prix . ' dh</h4>
								<div class="product-rating">
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
								</div>

                        <div class="product-btns">
						
									<button class="add-to-wishlist" data-product-id="' . $id . '">
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
    <form method="POST" action="ajouter_panier.php">
        <input type="hidden" name="ajouter" value="1">
        <input type="hidden" name="id" value="' . $row['id'] . '">
        <input type="hidden" name="nom" value="' . htmlspecialchars($row['nom']) . '">
        <input type="hidden" name="categorie" value="' . htmlspecialchars($row['categorie']) . '">
        <input type="hidden" name="prix" value="' . htmlspecialchars($row['prix']) . '">
        <input type="hidden" name="image" value="' . htmlspecialchars($row['image']) . '">
        
        <button type="submit" class="add-to-cart-btn" name="ajout">
            <i class="fa fa-shopping-cart"></i> add to cart
        </button>
    </form>
</div>

						</div>
					</div>
					';
				}
			} else {
				echo "<p>Aucun produit disponible.</p>";
			}
			?>
		</div>
	</div>
</div>








		<!-- FOOTER -->
		<footer id="footer">
			<!-- top footer -->
			<div class="section">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">About Us</h3>
								<p>Welcome to <b>Fornshop</b>,your go-to destinatioon for all things bookstore!we offer a wide range of high-quality stationery,school supplies,and educational materiels designed to support your learning and creative endeavors</p>
								<ul class="footer-links">
									<li><a href="#"><i class="fa fa-map-marker"></i>Rabat M</a></li>
									<li><a href="#"><i class="fa fa-phone"></i>+2120706405308</a></li>
									<li><a href="#"><i class="fa fa-envelope-o"></i>okamba.42@gmail.com</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Categories</h3>
								<ul class="footer-links">
									<li><a href="#">Stationery</a></li>
									<li><a href="#">Wrinting and Correction</a></li>
									<li><a href="#">Mathématics & Geometry Supplies</a></li>
									<li><a href="#">IT and multimedia</a></li>
									<li><a href="#">Bacpack</a></li>
								</ul>
							</div>
						</div>

						<div class="clearfix visible-xs"></div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Information</h3>
								<ul class="footer-links">
									<li><a href="#">About Us</a></li>
									<li><a href="#">Contact Us</a></li>
									<li><a href="#">Privacy Policy</a></li>
									<li><a href="#">Orders and Returns</a></li>
									<li><a href="#">Terms & Conditions</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Service</h3>
								<ul class="footer-links">
									<li><a href="#">My Account</a></li>
									<li><a href="#">View Cart</a></li>
									<li><a href="#">Wishlist</a></li>
									<li><a href="#">Track My Order</a></li>
									<li><a href="#">Help</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /top footer -->

			<!-- bottom footer -->
			<div id="bottom-footer" class="section">
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12 text-center">
							<ul class="footer-payments">
								<li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
								<li><a href="#"><i class="fa fa-credit-card"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
							</ul>
 
						</div>
					</div>
						<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /bottom footer -->
		</footer>
		<!-- /FOOTER -->

		<!-- jQuery Plugins -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
	<script>
document.addEventListener('DOMContentLoaded', function () {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');

    wishlistButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // empêche tout rechargement
            const productId = this.getAttribute('data-product-id');

            fetch('wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.text())
            .then(result => {
                if (result === 'success') {
                    alert('Produit ajouté à votre wishlist !');
                } else if (result === 'exists') {
                    alert('Ce produit est déjà dans votre wishlist.');
                } else if (result === 'non_connecte') {
                    alert('Veuillez vous connecter pour ajouter à votre wishlist.');
                } else {
                    alert('Erreur lors de l\'ajout à la wishlist.');
                }
            })
            .catch(error => {
                console.error('Erreur Ajax :', error);
                alert('Erreur de communication avec le serveur.');
            });
        });
    });
});
</script>

</html>
