<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Profile creator page">
    <meta name="author" content="Jessie Davis 200433256">
    <title><?php echo $pageTitle ?? 'The Infinite Library'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <!-- Top part of header -->
    <div class="headerTop">
        <!-- clickable logo -->
        <div class="logo">
            <a href="index.php">
                <img src="Assets/Images/TheInfiniteLibraryLogo.png" alt="The Infinite Library Logo"></a>
        </div>
        <!-- Search bar -->
        <div class="searchBar">
            <form action="Shop.php" method="GET" class="searchForm">
                <input
                        type="search"
                        name="searchBar"
                        placeholder="Find your next read!"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"

                <button type="submit" class="searchBtn">
                    <img src="Assets/Icons/magGlassIcon.svg" alt="Search" class="Icon">
                </button>
            </form>
        </div>
        <!-- Fav Acc Cart -->
        <div class="headUserItems">
            <!-- Fav -->
            <a href="#" class="linkIcon">
                <img src="Assets/Icons/heartIcon.svg" alt="Favourites" class="Icon"></a>
            <!-- Acc -->
            <a href="#" class="linkIcon">
                <img src="Assets/Icons/Account.svg" alt="Account" class="Icon"></a>
            <!-- Cart -->
            <a href="#" class="linkIcon">
                <img src="Assets/Icons/cartIcon.svg" alt="Cart" class="Icon"></a>
        </div>
        <!-- Login button -->
        <div class="loginBtn">
            <a href="RegLog.php" class="btn">Login</a>
        </div>
    </div>
    <!-- nav under search and user actions -->
    <nav class="headNav">
        <ul class="navLinks">
            <li><a href="Shop.php">Browse</a></li>
            <li><a href="About.php">About</a></li>
            <li><a href="Help.php">Help</a></li>
        </ul>
    </nav>
</header>
<main class="mainContent">
