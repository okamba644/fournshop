<?php
include "connexion_base.php";
session_start();
$categories = [];
$sql_cat = "SELECT DISTINCT categorie FROM produits ORDER BY categorie ASC";
$result_cat = $mysqli->query($sql_cat);
if ($result_cat->num_rows > 0) {
    while ($row = $result_cat->fetch_assoc()) {
        $categories[] = $row['categorie'];
    }
}

// Récupérer min et max prix pour l'intervalle
$sql_prix = "SELECT MIN(prix) as minPrix, MAX(prix) as maxPrix FROM produits";
$res_prix = $mysqli->query($sql_prix);
$minPrix = 0;
$maxPrix = 1000;
if ($res_prix && $row_prix = $res_prix->fetch_assoc()) {
    $minPrix = floor($row_prix['minPrix']);
    $maxPrix = ceil($row_prix['maxPrix']);
}

// Variables de filtrage et pagination
$filtreCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
$filtrePrixMin = isset($_GET['prix_min']) ? floatval($_GET['prix_min']) : $minPrix;
$filtrePrixMax = isset($_GET['prix_max']) ? floatval($_GET['prix_max']) : $maxPrix;

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$produitsParPage = 15;
$offset = ($page - 1) * $produitsParPage;

// Construction de la requête SQL avec filtres
$whereClauses = [];
$params = [];
$types = "";

if (!empty($filtreCategories)) {
    // Préparer un IN (?, ?, ...) selon le nombre de catégories
    $placeholders = implode(',', array_fill(0, count($filtreCategories), '?'));
    $whereClauses[] = "categorie IN ($placeholders)";
    foreach ($filtreCategories as $cat) {
        $params[] = $cat;
        $types .= "s";
    }
}

$whereClauses[] = "prix BETWEEN ? AND ?";
$params[] = $filtrePrixMin;
$params[] = $filtrePrixMax;
$types .= "dd";

$whereSql = "";
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Requête pour compter le total des produits filtrés
$sqlCount = "SELECT COUNT(*) as total FROM produits $whereSql";
$stmtCount = $mysqli->prepare($sqlCount);
if ($types !== "") {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$resCount = $stmtCount->get_result();
$totalProduits = 0;
if ($resCount && $rowCount = $resCount->fetch_assoc()) {
    $totalProduits = intval($rowCount['total']);
}
$totalPages = ceil($totalProduits / $produitsParPage);

// Requête principale pour récupérer les produits filtrés et paginés
$sql = "SELECT * FROM produits $whereSql ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($sql);

$paramsWithLimit = $params;
$typesWithLimit = $types . "ii";
$paramsWithLimit[] = $produitsParPage;
$paramsWithLimit[] = $offset;

$stmt->bind_param($typesWithLimit, ...$paramsWithLimit);
$stmt->execute();
$result = $stmt->get_result();


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
								<a href="index.php" class="logo">
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
    <a class="dropdown-toggle" href="panier.php" aria-expanded="true">
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
?></div>
    </a>
</div>

							</a>
									<div class="cart-dropdown">
									
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

		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb-tree">
							<li><a href="#">Home</a></li>
							<li><a href="#">All Categories</a></li>
							<li><a href="#">Accessories</a></li>
							<li class="active">Headphones (227,490 Results)</li>
						</ul>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /BREADCRUMB -->

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- ASIDE -->
					<div class="sidebar">
    <h3>Filtrer par catégorie</h3>
    <form method="GET" id="filterForm">
        <div class="filter-group">
            <?php foreach ($categories as $cat): ?>
                <div>
                    <input type="checkbox" name="categories[]" id="cat-<?php echo htmlspecialchars($cat); ?>"
                        value="<?php echo htmlspecialchars($cat); ?>"
                        <?php if(in_array($cat, $filtreCategories)) echo "checked"; ?>>
                    <label for="cat-<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Filtrer par prix</h3>
        <div class="filter-group">
            <label for="prix_min">Prix min :</label><br>
            <input type="number" name="prix_min" id="prix_min" value="<?php echo htmlspecialchars($filtrePrixMin); ?>" min="<?php echo $minPrix; ?>" max="<?php echo $maxPrix; ?>"><br><br>
            <label for="prix_max">Prix max :</label><br>
            <input type="number" name="prix_max" id="prix_max" value="<?php echo htmlspecialchars($filtrePrixMax); ?>" min="<?php echo $minPrix; ?>" max="<?php echo $maxPrix; ?>">
        </div>

        <button type="submit">Filtrer</button>
    </form>
</div>
						
						<!-- /aside Widget -->
					</div>
					<!-- /ASIDE -->

					
 <div class="section">
	<div class="container">
		<div class="row">
			<?php


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




												<!-- /store products -->

						<!-- store bottom filter -->
						<div class="store-filter clearfix">
							<span class="store-qty">Showing 20-100 products</span>
							<ul class="store-pagination">
								<li class="active">1</li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#"><i class="fa fa-angle-right"></i></a></li>
							</ul>
						</div>
						<!-- /store bottom filter -->
					</div>
					<!-- /STORE -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		
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
</html>
