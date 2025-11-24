<?php
//Starts session if not already one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sets base filepath if in admin folder
$inAdmin = strpos($_SERVER['PHP_SELF'], "/Admin/") !== false;
$basePath = $inAdmin ? "../" : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? "Infinite Knowledge at the Infinite Library"); ?>">
    <meta name="author" content="Jessie Davis 200433256">
    <title><?php echo $pageTitle ?? 'The Infinite Library'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;1,500&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/style.css">
</head>
<body>
<header>
    <!-- Top part of header -->
    <div class="headerTop">
        <!-- clickable logo -->
        <div class="logo">
            <a href="<?php echo $basePath; ?>index.php">
                <img src="<?php echo $basePath; ?>Assets/Images/TheInfiniteLibraryLogo.png" alt="The Infinite Library
                Logo"></a>
        </div>
        <!-- Search bar -->
        <div class="searchBar">

            <!-- If on admin management page search there, otherwise goes to shop -->
            <form action="<?php
            if (strpos($_SERVER['PHP_SELF'], "manageProducts.php") !== false) {
                echo "manageProducts.php";
            } else {
                echo $basePath . "Shop.php";
            }
            ?>"method="GET" class="searchForm">
                <input
                        type="search"
                        name="search"
                        placeholder="Find your next read!"
                        value="<?php echo isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : ""; ?>">

                <button type="submit" class="searchBtn">
                    <img src="<?php echo $basePath; ?>Assets/Icons/magGlassIcon.svg" alt="Search" class="Icon">
                </button>
            </form>
        </div>
        <!-- Fav Acc Cart Admin Dash -->
        <div class="headUserItems">

            <!-- Fav -->
            <a href="#" class="linkIcon">
                <img src="<?php echo $basePath; ?>Assets/Icons/heartIcon.svg" alt="Favourites" class="Icon"></a>
            <!-- Acc -->
            <a href="#" class="linkIcon">
                <img src="<?php echo $basePath; ?>Assets/Icons/Account.svg" alt="Account" class="Icon"></a>
            <!-- Cart -->
            <a href="#" class="linkIcon">
                <img src="<?php echo $basePath; ?>Assets/Icons/cartIcon.svg" alt="Cart" class="Icon"></a>
        </div>
        <!-- Login button -->
        <div class="loginBtn">
            <?php if(isset($_SESSION["userId"])): ?>
                <a href="<?php echo $basePath; ?>Logout.php" class="btn">Logout</a>
            <?php else: ?>
            <a href="<?php echo $basePath; ?>Login.php" class="btn">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <!-- nav under search and user actions -->
    <nav class="headNav">
        <ul class="navLinks">
            <li><a href="<?php echo $basePath; ?>Shop.php">Browse</a></li>
            <li><a href="<?php echo $basePath; ?>About.php">About</a></li>
            <li><a href="<?php echo $basePath; ?>Help.php">Help</a></li>

            <!-- Admin dashboard link (only displays to admins) -->
            <?php if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]): ?>
                <li><a href="<?php echo $inAdmin ? "Dashboard.php" : $basePath . "Admin/Dashboard.php";
                ?>">Dashboard</a></li>
            <?php endif; ?>

        </ul>
    </nav>
</header>