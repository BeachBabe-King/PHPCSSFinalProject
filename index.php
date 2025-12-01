<?php
require_once  "Config/Config.php";
require_once  "Classes/Database.php";
// Page metadata
$pageTitle = "Infinite Library Homepage";
$pageDescription = "Infinite knowledge for quality prices";

// Database connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch featured books for carousel
try {
    $featuredStmt = $pdo->prepare("
        SELECT id, name, author, price, image
        FROM FPproduct
        WHERE isFeatured = 1
        ORDER BY price DESC 
        LIMIT 18
    ");
    $featuredStmt->execute();
    $featuredBooks = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featuredBooks = [];
    error_log("Feature book error: " . $e->getMessage());
}

// Fetch new release books for carousel
try {
    $newReleasesStmt = $pdo->prepare("
    SELECT id, name, author, price, image
    FROM FPproduct
    ORDER BY createdAt DESC
    LIMIT 18
    ");
    $newReleasesStmt->execute();
    $newReleases = $newReleasesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $newReleases = [];
    error_log("New release error: " . $e->getMessage());
}

require_once  "Templates/Header.php"; ?>

    <!-- Success message -->
<?php if (isset($_GET["success"]) && $_GET["success"] === "registered"): ?>
    <div class="Container">
        <div class="successMessage">
            <p>Your account has been created successfully, Welcome!</p>
            <button class="closeMsg" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>
        </div>
    </div>
<?php endif; ?>

<!-- Hero section -->
<section class="hero">
    <div class="heroContent">
        <h1>Stories Never End</h1>
        <p>The Infinite Library is a place, where knowledge and imagination has no limits
        Step into endless worlds, one page at a time.</p>
        <a href="Shop.php">Browse Our Shelves</a>
    </div>
</section>

<!-- Featured books carousel -->
<section class="carouselContainer">
    <div class="Container">
        <div class="sectionHeader">
            <h2>Featured Books</h2>
        </div>

        <?php if (!empty($featuredBooks)): ?>
            <div class="carouselWrapper">
                <button class="carouselBtn carouselBtnPrev">
                    <span>&lsaquo;</span>
                </button>

                <div class="carouselTrack">
                    <?php foreach ($featuredBooks as $product): ?>
                        <?php include "Templates/carouselCard.php"; ?>
                    <?php endforeach; ?>
                </div>

                <button class="carouselBtn carouselBtnNext">
                    <span>&rsaquo;</span>
                </button>
            </div>
        <?php else: ?>
            <p class="noBooksMsg">No featured titles right now, check again later</p>
        <?php endif; ?>
    </div>
</section>

<!-- New release carousel -->
<section class="carouselContainer">
    <div class="Container">
        <div class="sectionHeader">
            <h2>New Releases</h2>
        </div>

        <?php if (!empty($newReleases)): ?>
            <div class="carouselWrapper">
                <button class="carouselBtn carouselBtnPrev">
                    <span>&lsaquo;</span>
                </button>

                <div class="carouselTrack">
                    <?php foreach ($newReleases as $product): ?>
                        <?php include "Templates/carouselCard.php"; ?>
                    <?php endforeach; ?>
                </div>

                <button class="carouselBtn carouselBtnNext">
                    <span>&rsaquo;</span>
                </button>
            </div>
        <?php else: ?>
            <p class="noBooksMsg">No new releases right now, check again later</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once  "Templates/Footer.php"; ?>