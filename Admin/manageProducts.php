<?php
require_once "../Config/Config.php";
require_once "../Classes/Database.php";
require_once "../Classes/Product.php";

session_start();

//check if user is logged in and is admin
if (!isset($_SESSION["userId"]) || !$_SESSION["isAdmin"]) {
    header("Location: ../Login.php");
    exit();
}

$pageTitle = "Manage Products";
$pageDescription = "View, edit, and delete products";

$errors = [];
$success = "";

//Database connection
$database = new Database();
$pdo = $database->getConnection();
$productModel = new Product($pdo);

// Handle delete action
if (isset($_GET["action"]) && $_GET["action"] === "delete" && isset($_GET["id"])) {
    $deleteId = intval($_GET["id"]);
    $currentPage = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;

    try {
        if ($productModel->productExists($deleteId)) {
            $productModel->deleteProduct($deleteId);
            header("Location: manageProducts.php?success=deleted&page=" . $currentPage);
            exit();
        } else {
            header("Location: manageProducts.php?error=notfound&page=" . $currentPage);
            exit();
        }
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        header("Location: manageProducts.php?error=deletefailed&page=" . $currentPage);
        exit();
    }
}

// Success messages
if (isset($_GET["success"])) {
    if ($_GET["success"] === "updated") {
        $success = "Product updated successfully!";
    } elseif ($_GET["success"] === "deleted") {
        $success = "Product deleted successfully!";
    }
}

// Error messages
if (isset($_GET["error"])) {
    if ($_GET["error"] === "notfound") {
        $errors[] = "Product not found";
    } elseif ($_GET["error"] === "deletefailed") {
        $errors[] = "Could not delete product";
    }
}

//Pagination if more than 20 books
$booksPerPage = 20;
$currentPage = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$offset = ($currentPage - 1) * $booksPerPage;

// Search functionality
$searchQuery = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$whereStmt = [];
$params = [];

//Search filter
if (!empty($searchQuery)) {
    $whereStmt[] = "(name LIKE ? OR description LIKE ? OR author LIKE ?)";
    $searchParam = "%" . $searchQuery . "%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

// Build WHERE if filters present
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
    $productSQL = "SELECT id, name, author, price, image, category, pageCount, stock 
            FROM FPproduct
            $whereCombined
            ORDER BY name ASC 
            LIMIT $booksPerPage OFFSET $offset";

    $productsStmt = $pdo->prepare($productSQL);
    $productsStmt->execute($params);
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    error_log("Can't fetch products " . $e->getMessage());
}

require_once "../Templates/Header.php"; ?>

    <section class="pageHeader">
        <h1>Manage Products</h1>
    </section>

    <main class="Container">

        <!-- Success message -->
        <?php if (!empty($success)): ?>
            <div class="successMessage">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <!-- Error messages -->
        <?php if (!empty($errors)): ?>
            <div class="errorMessages">
                <?php foreach ($errors as $error): ?>
                    <p class="errorMessage"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
                    <?php include "../Templates/adminProductCard.php"; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="noProducts">
                    <h3>No Products Found</h3>
                    <p>Try a different search or add products</p>
                    <a class="submitBtn" href="addProduct.php">Add New Product</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <?php
            // Build filter params for pagination
            $filterParams = [];
            if (!empty($searchQuery)) {
                $filterParams['search'] = $searchQuery;
            }
            ?>

            <nav class="Pagination">
                <ul class="paginationList">

                    <!-- Previous -->
                    <?php if ($currentPage > 1): ?>
                        <li class="paginationItem">
                            <?php $filterParams["page"] = $currentPage - 1; ?>
                            <a href="?<?php echo http_build_query($filterParams); ?>" class="paginationLink">
                                &laquo; Previous
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="paginationItem">
                            <?php $filterParams["page"] = $i ?>
                            <a href="?<?php echo http_build_query($filterParams); ?>"
                               class="paginationLink <?php echo $i === $currentPage ? "active" : ""; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next  -->
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="paginationItem">
                            <?php $filterParams["page"] = $currentPage + 1; ?>
                            <a href="?<?php echo http_build_query($filterParams); ?>" class="paginationLink">
                                Next &raquo;
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>
        <?php endif; ?>
    </main>

<?php require_once "../Templates/footer.php"; ?>