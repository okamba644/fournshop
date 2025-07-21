<?php
session_start();
include "connexion_base.php";


$categories = []; // je recupère les categories dans uun tableau
$sql_cat = "SELECT DISTINCT categorie FROM produits ORDER BY categorie ASC";
$result_cat = $mysqli->query($sql_cat);
if ($result_cat->num_rows > 0) {
    while ($row = $result_cat->fetch_assoc()) {
        $categories[] = $row['categorie'];// chaque categorie est placée dans le tableau
    }
}


$sql_prix = "SELECT MIN(prix) as minPrix, MAX(prix) as maxPrix FROM produits";// je recupère le minimun et le maximun des prix de chaque produit
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


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Filtrer les produits</title>
    <link rel="stylesheet" href="style.css"> <!-- Ta feuille de style -->
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

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
        }
        .sidebar {
            width: 250px;
            padding-right: 20px;
            border-right: 1px solid #ccc;
        }
        .content {
            flex: 1;
            padding-left: 20px;
        }
        .product {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 10px;
            width: 220px;
            display: inline-block;
            vertical-align: top;
            margin-right: 15px;
        }
        .product-img img {
            max-width: 100%;
            height: auto;
        }
        .filter-group {
            margin-bottom: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            margin: 0 5px;
            text-decoration: none;
            color: blue;
        }
        .pagination .current {
            font-weight: bold;
            color: black;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php




?>
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

<div class="content">
    <h2>Produits</h2>
    <div>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $nom = htmlspecialchars($row['nom']);
            $categorie = htmlspecialchars($row['categorie']);
            $prix = htmlspecialchars($row['prix']);
            $image = htmlspecialchars($row['image']);

            echo '
            <div class="product">
                <div class="product-img">
                    <img src="img/' . $image . '" alt="">
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
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="hidden" name="nom" value="' . $nom . '">
                        <input type="hidden" name="categorie" value="' . $categorie . '">
                        <input type="hidden" name="prix" value="' . $prix . '">
                        <input type="hidden" name="image" value="' . $image . '">
                        <button type="submit" class="add-to-cart-btn" name="ajout">
                            <i class="fa fa-shopping-cart"></i> add to cart
                        </button>
                    </form>
                </div>
            </div>
            ';
        }
    } else {
        echo "<p>Aucun produit disponible avec ces filtres.</p>";
    }
    ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        $urlBase = $_SERVER['PHP_SELF'] . "?";

        // Garde les filtres dans l'url
        $paramsUrl = $_GET;
        unset($paramsUrl['page']); // on gère la page séparément

        // Génère lien page
        function pageLink($num, $params) {
            $params['page'] = $num;
            return $_SERVER['PHP_SELF'] . "?" . http_build_query($params);
        }

        if ($page > 1) {
            echo '<a href="'.pageLink($page-1, $paramsUrl).'">&laquo; Précédent</a>';
        }

        for ($i=1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<span class="current">'.$i.'</span>';
            } else {
                echo '<a href="'.pageLink($i, $paramsUrl).'">'.$i.'</a>';
            }
        }

        if ($page < $totalPages) {
            echo '<a href="'.pageLink($page+1, $paramsUrl).'">Suivant &raquo;</a>';
        }
        ?>
    </div>
</div>

</body>
</html>
