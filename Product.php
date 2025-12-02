<!-- Individual product view -->
<?php
require_once "Config/Config.php";
require_once "Classes/Database.php";

// Product id from URL
$productId = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

// Database connect
$database = new Database();
$pdo = $database->getConnection();

//Fetch product data
try {
    $stmt = $pdo->prepare("
SELECT id, name, author, description, price, image, category, pageCount, stock
FROM FPproduct
WHERE id = ?");

    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    //If no product, display product not found
    if (!$product) {
        $pageTitle = "Product not found";
        $pageDescription = "The product you are looking for does not exist";

        require_once "Templates/Header.php";
        ?>
        <section class="pageHeader">
            <div class="Container">
                <h1>Product Not Found</h1>
            </div>
        </section>

        <main class="Container">
            <div class="notFoundMsg">
                <p>Sorry, we're unable to find what you're looking for.</p>
                <a href="Shop.php" class="submitBtn">Browse our collection</a>
            </div>
        </main>

        <?php
        require_once "Templates/Footer.php";
        exit();
    }
} catch (PDOException $e) {
    error_log("Can't fetch product" . $e->getMessage());
    header("Location: Shop.php");
    exit();
}

$pageTitle = htmlspecialchars($product["name"]);
$pageDescription = "Buy " . htmlspecialchars($product["name"]) . " by " . htmlspecialchars($product["author"]);

require_once "Templates/Header.php";
?>

<section class="pageHeader">
    <div class="Container">
        <a href="Shop.php" class="backPageLink">&larr; Back to collection</a>
    </div>
</section>

<main class="Container">
    <div class="productDetailsContainer">

        <!-- Product image -->
        <div class="productDetailsImg">
            <?php if (!empty($product["image"])): ?>
                <img src="<?php echo htmlspecialchars($product["image"]); ?>"
                     alt="<?php echo htmlspecialchars($product["name"]); ?> cover">
            <?php else: ?>
                <img src="Assets/Images/bookCoverPlaceholder.png"
                     alt="Book Cover Placeholder">
            <?php endif; ?>
        </div>

        <!-- Product info -->
        <div class="productDetailsInfo">
            <h1 class="detailsTitle"><?php echo htmlspecialchars($product["name"]); ?></h1>
            <p class="detailsAuthor">By <?php echo htmlspecialchars($product["author"]); ?></p>
            <p class="detailsCategory">
                Genre: <strong><?php echo htmlspecialchars($product["category"]); ?></strong>
            </p>
            <p class="detailsPrice">
                $ <?php echo number_format($product["price"], 2); ?>
            </p>
            <p class="detailsPageCount">
                Pages: <strong><?php echo htmlspecialchars($product["pageCount"]); ?></strong>
            </p>
            <p class="detailsStock">
                <?php if ($product["stock"] > 0): ?>
                    Available: <?php echo htmlspecialchars($product["stock"]); ?>
                <?php else: ?>
                    Out of stock
                <?php endif; ?>
            </p>
            <div class="detailsUserAction">
                <?php if ($product["stock"] > 0): ?>
                    <button class="addCartBtn">Add to cart</button>
                <?php else: ?>
                    <button class="addCartBtn" disabled>Out of stock</button>
                <?php endif; ?>

                <a href="#" class="linkIcon detailsFav">
                    <img src="Assets/Icons/heartIcon.svg" alt="Favourites" class="Icon"></a>
            </div>
        </div>
    </div>

    <!-- Book description -->
    <?php if (!empty($product["description"])): ?>
        <div class="detailsDescription">
            <h2>Description</h2>
            <p><?php echo nl2br(htmlspecialchars($product["description"])); ?></p>
        </div>
    <?php endif; ?>
</main>

<?php require_once "Templates/Footer.php"; ?>