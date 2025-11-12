<?php
require_once  "Config/Config.php";
require_once  "Classes/Database.php";
// Page metadata
$pageTitle = "Infinite Library Homepage";
$pageDescription = "Infinite knowledge for quality prices";

require_once  "Templates/Header.php";
?>

<!-- hero section -->
<section class="hero">
    <div class="heroContent">
        <h1>Stories Never End</h1>
        <p>The Infinite Library is a place, where knowledge and imagination has no limits
        Step into endless worlds, one page at a time.</p>
        <a href="Shop.php">Browse Our Shelves</a>
        <!-- background image here -->
    </div>
</section>

<!-- Featured books carousel -->
<section class="featuredBooksCarousel">
    <div class="Container">
        <div class="sectionHeader">
            <h2>Featured Books</h2>
        </div>
        <!-- holds all carousel elements -->
        <div class="carouselContainer">
            <button class="carouselBtn carouselBtnPrev">
                <span>&lsaquo;</span>
            </button>
        </div>

    </div>
</section>

<main class="Container">
