<!-- Admin landing page -->
<?php
require_once "../Config/Config.php";
require_once "../Classes/Database.php";

session_start();

//check if user is logged in and is admin
if (!isset($_SESSION["userId"]) || !$_SESSION["isAdmin"]) {
    header("Location: ../Login.php");
    exit();
}

$pageTitle = "Admin Dashboard";
$pageDescription = "Manage the Infinite Library ";

require_once "../Templates/Header.php";
?>

<section class="pageHeader">
    <h1>Manage the shelves</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION["userName"]) ?></p>
</section>

<main class="Container">
    <div class="dashboardCards">

        <!-- Add products manually -->
        <a href="addProduct.php" class="dashboardCard">
            <div class="dashboardIcon">
                <img src="../Assets/Icons/addBookIcon.svg" alt="Add book icon">
            </div>
            <h3>Add products</h3>
            <p>Manually add a new book to the shelves</p>
        </a>

        <!-- Manage products -->
        <a href="manageProducts.php" class="dashboardCard">
            <div class="dashboardIcon">
                <img src="../Assets/Icons/editIcon.svg" alt="Edit icon">
            </div>
            <h3>Manage the shelves</h3>
            <p>View, edit, and delete items from shelves</p>
        </a>

        <!-- Import from Google books (bonus) -->
        <a href="importBooks.php" class="dashboardCard">
            <div class="dashboardIcon">
                <img src="../Assets/Icons/importIcon.svg" alt="Import icon"
            </div>
            <h3>Import from Google books</h3>
            <p>Search and import books from google database</p>
        </a>

        <!-- Analytics (bonus) -->
        <a href="Analytics.php" class="dashboardCard">
            <div class="dashboardIcon">
                <img src="../Assets/Icons/analyticsIcon.svg" alt="AnalyticsIcon">
            </div>
            <h3>Site stats</h3>
            <p>View total products, registered users, and stock alerts</p>
        </a>

    </div>
</main>

<?php require_once "../Templates/Footer.php"; ?>