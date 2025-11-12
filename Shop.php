<!-- All product view -->
<?php
require_once "Config/Config.php";
require_once "Classes/Database.php";

$pageTitle = "Shop All Books";
$pageDescription = "Browse our collection in any genre";

//initialize db connection
$database = new Database();
$pdo = $database->getConnection();

//Pagination
$booksPerPage = 20;
$currentPage = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$offset = ($currentPage - 1) * $booksPerPage;

//Book filters parameters
$selectedGenre = isset($_GET["genre"]) ? $_GET["genre"] : [];
$selectedPrice = isset($_GET["price"]) ? $_GET["price"] : [];
$selectedPageCount = isset($_GET["pages"]) ? $_GET["pages"] : [];
$searchQuery = isset($_GET["search"]) ? trim($_GET["search"]) : "";

//Build search
$whereStmt = [];
$params = [];

//Genre filter
if (!empty($selectedGenre)) {
    if (!is_array($selectedGenre)) {
        $selectedGenre = [$selectedGenre];
    }
    $genreStmt = [];

    foreach ($selectedGenre as $genre) {
        if ($genre != "All") {
            $genreStmt[] = "category = ?";
            $params[] = $genre;
        }
    }

    if (!empty($genreStmt)) {
        $whereStmt[] = "(" . implode(" OR ", $genreStmt) . ")";
    }
}

//Price filter
if (!empty($selectedPrice)) {
    if (!is_array($selectedPrice)) {
        $selectedPrice = [$selectedPrice];
    }
    $priceStmt = [];

    foreach ($selectedPrice as $Price) {
        switch ($Price) {
            case "under10":
                $priceStmt[] = "price < 10";
                break;
            case "10-20":
                $priceStmt[] = "price BETWEEN 10 AND 20";
                break;
            case "20-30":
                $priceStmt[] = "price BETWEEN 20 AND 30";
                break;
            case "30-50":
                $priceStmt[] = "price BETWEEN 30 AND 50";
                break;
            case "over50":
                $priceStmt[] = "price > 50";
                break;
        }
    }
    if (!empty($priceStmt)) {
        $whereStmt[] = "(" . implode(" OR ", $priceStmt) . ")";
    }
}

//Page count filter
if (!empty($selectedPageCount)) {
    if (!is_array($selectedPageCount)) {
        $selectedPageCount = [$selectedPageCount];
    }
    $pageCountStmt = [];

    foreach ($selectedPageCount as $pageCount) {
        switch ($pageCount) {
            case "under250":
                $pageCountStmt[] = "pageCount < 250";
                break;
            case "250-450":
                $pageCountStmt[] = "pageCount BETWEEN 250 AND 450";
                break;
            case "450-650":
                $pageCountStmt[] = "pageCount BETWEEN 450 AND 650";
                break;
            case "over650":
                $pageCountStmt[] = "pageCount > 650";
                break;
        }
    }

    if (!empty($pageCountStmt)) {
        $whereStmt[] = "(" . implode(" OR ", $pageCountStmt) . ")";
    }
}

//Search
if (!empty($searchQuery)) {
    $whereStmt[] = "(name LIKE ? OR description LIKE ? OR author LIKE ?)";
    $searchParam = "%" . $searchQuery . "%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

//Combine statements
$whereCombined = !empty($whereStmt) ? "WHERE " . implode(" AND ", $whereStmt) : "";

//Total books
try {
    $Count = "SELECT COUNT(*) as total FROM FPproduct $whereCombined";
    $countStmt = $pdo->prepare($Count);
    $countStmt->execute($params);
    $totalBooks = $countStmt->fetch(PDO::FETCH_ASSOC)["total"];
    $totalPages = ceil($totalBooks / $booksPerPage);
} catch (PDOException $e) {
    $totalBooks = 0;
    $totalPages = 0;
    error_log("Count error: " . $e->getMessage());
}

//Gets products
try {
    $productParams = $params;
    $productParams[] = $booksPerPage;
    $productParams[] = $offset;

    $productSQL = "SELECT id, name, author, price, image, category, pageCount 
            FROM FPproduct
            $whereCombined
            ORDER BY name ASC 
            LIMIT ? OFFSET ?";
    $productsStmt = $pdo->prepare($productSQL);
    $productsStmt->execute($productParams);
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    error_log("Can't fetch products " . $e->getMessage());
}

//Dynamic genres
try {
    $genreStmt = $pdo->query(
        "SELECT DISTINCT category 
                    FROM FPproduct 
                    WHERE category IS NOT NULL 
                    ORDER BY category ASC");
    $genres = $genreStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $genres = [];
    error_log("Can't fetch genres " . $e->getMessage());
}

require_once "Templates/Header.php";
?>

<section class="pageHeader">
    <h1>Browse Our Collection</h1>
</section>

<main class="Container">
    <div id="shopLayout">

        <!-- Sidebar filters -->
        <aside id="filterSidebar">
            <h3>Filter Books</h3>
            <form method="GET" action="Shop.php" id="filtersForm">

                <!-- Keep nav search if exists -->
                <?php if (!empty($searchQuery)): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <?php endif; ?>

                <!-- Genre filter -->
                <div class="filterOptions">
                    <label for="genre">Genre</label>
                    <?php

                    //Makes array if not
                    if (!is_array($selectedGenre)) {
                        $selectedGenre = $selectedGenre !== "" ? [$selectedGenre] : [];
                    } ?>

                    <!-- Makes checkbox for each option -->
                    <?php foreach ($genres as $genre): ?>
                        <label class="checkboxLabel">
                            <input
                                    type="checkbox"
                                    name="genre[]"
                                    value="<?php echo htmlspecialchars($genre); ?>"
                                <?php echo in_array($genre, $selectedGenre) ? "checked" : ""; ?>
                            >
                            <span><?php echo htmlspecialchars($genre) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Price range -->
                <div class="filterOptions">
                    <label>Price Range</label>
                    <?php
                    $priceOptions = [
                        "under10" => "Under $10",
                        "10-20" => "$10 - $20",
                        "20-30" => "$20 - $30",
                        "30-50" => "$30 - $50",
                        "over50" => "Over $50",
                    ];
                    //Make price an array if not
                    if (!is_array($selectedPrice)) {
                        $selectedPrice = $selectedPrice !== "" ? [$selectedPrice] : [];
                    }
                    ?>
                    <!-- Makes checkbox for each option -->
                    <?php foreach ($priceOptions as $value => $label): ?>
                        <label class="checkboxLabel">
                            <input
                                    type="checkbox"
                                    name="price[]"
                                    value="<?php echo $value; ?>"
                                <?php echo in_array($value, $selectedPrice) ? "checked" : ""; ?>>
                            <span><?php echo $label ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>


                <!-- Page count -->
                <div class="filterOptions">
                    <label for="pages">Page Count</label>
                    <?php
                    $pageCountOptions = [
                        "under250" => "Under 250 pages",
                        "250-450" => "250 to 450 pages",
                        "450-650" => "450 to 650 pages",
                        "over650" => "Over 650 pages"
                    ];
                    //make an array if not
                    if (!is_array($selectedPageCount)) {
                        $selectedPageCount = $selectedPageCount !== "" ? [$selectedPageCount] : [];
                    }
                    ?>

                    <!-- Makes checkbox for each option -->
                    <?php foreach ($pageCountOptions as $value => $label): ?>
                        <label class="checkboxLabel">
                            <input
                                    type="checkbox"
                                    name="pages[]"
                                    value="<?php echo $value; ?>"
                                <?php echo in_array($value, $selectedPageCount) ? "checked" : ""; ?>
                            >
                            <span><?php echo $label; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Filter buttons -->
                <div id="filterActions">
                    <button type="submit" class="submitBtn">Apply Filters</button>
                    <a href="Shop.php" class="clearBtn">Clear Filters</a>
                </div>
            </form>
        </aside>

        <!-- Prodcut area -->
        <div id="shopContent">

            <!-- How many books displayed -->
            <div class="resultsHeader">
                <p class="resultsCount">
                    Showing <?php echo count($products); ?> of <?php echo $totalBooks; ?> books
                    <?php if (!empty($searchQuery)): ?>
                        for "<?php echo htmlspecialchars($searchQuery); ?>"
                    <?php endif; ?>
                </p>
            </div>

            <!-- Product cards -->
            <div class="productGrid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <!-- Product card template NEEDED -->
                        <?php include "Templates/productCard.php"; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="noProducts">
                        <h3>No Books Found</h3>
                        <p>Try changing your search</p>
                        <a class="submitBtn" href="Shop.php">View All</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>

                <?php
                // Filter string for pagination
                $filterParams = "";

                // Genre checkbox array
                if (!empty($selectedGenre) && is_array($selectedGenre)) {
                    foreach ($selectedGenre as $genre) {
                        if ($genre !== "All") {
                            $filterParams .= "&genre[]=" . urlencode($genre);
                        }
                    }
                }

                // Price checkbox array
                if (!empty($selectedPrice) && is_array($selectedPrice)) {
                    foreach ($selectedPrice as $price) {
                        $filterParams .= "&price[]=" . urlencode($price);
                    }
                }

                // Page count checkbox array
                if (!empty($selectedPageCount) && is_array($selectedPageCount)) {
                    foreach ($selectedPageCount as $pageCount) {
                        $filterParams .= "&pages[]=" . urlencode($pageCount);
                    }
                }

                // Search
                if (!empty($searchQuery)) {
                    $filterParams .= "&search=" . urlencode($searchQuery);
                }
                ?>

                <nav class="Pagination">
                    <ul class="paginationList">

                        <!-- Previous -->
                        <?php if ($currentPage > 1): ?>
                            <li class="paginationItem">
                                <a href="?page=<?php echo $currentPage - 1 . $filterParams; ?>" class="paginationLink">
                                    &laquo; Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="paginationItem">
                                <a href="?page=<?php echo $i . $filterParams; ?>"
                                   class="paginationLink <?php echo $i === $currentPage ? "active" : ""; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next  -->
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="paginationItem">
                                <a href="?page=<?php echo $currentPage + 1 . $filterParams; ?>" class="paginationLink">
                                    Next &raquo;
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
require 'Templates/footer.php';
?>