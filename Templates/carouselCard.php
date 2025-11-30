<!-- Carousel card template -->
<article class="carouselCard">
    <a href="Product.php?id=<?php echo $product["id"]; ?>" class="carouselCardLink">

        <!-- Book cover -->
        <div class="productImageContainer">
            <?php if (!empty($product["image"])): ?>
                <img src="<?php echo htmlspecialchars($product["image"]); ?>"
                     alt="<?php echo htmlspecialchars($product["name"]); ?>"
                     class="productImage">
            <?php else: ?>
                <img src="Assets/Images/bookCoverPlaceholder.png"
                     alt="Book Cover Placeholder"
                     class="productImage">
            <?php endif; ?>
        </div>

        <!-- Title and Price -->
        <div class="carouselCardInfo">
            <h4 class="carouselTitle"><?php echo htmlspecialchars($product["name"]); ?></h4>
            <div class="carouselPrice">
                <p>$<?php echo number_format($product["price"], 2); ?></p>
            </div>
        </div>
    </a>
    <!-- Add to cart button -->
    <div class="addToCartBtn">
        <button>Buy Now</button>
    </div>
</article>