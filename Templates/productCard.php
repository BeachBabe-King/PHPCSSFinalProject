<!-- Product card template -->
<article class="productCard">
    <a href="Product.php?id=<?php echo htmlspecialchars($product["id"]); ?>" class="productCardLink">
        <!-- Product image -->
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

        <!-- Product info -->
        <div class="productInfo">
            <h3 class="productTitle"><?php echo htmlspecialchars($product["name"]); ?></h3>
            <p class="productAuthor"><?php echo htmlspecialchars($product["author"]); ?></p>

            <div class="productData">
                <span class="productCategory"><?php echo htmlspecialchars($product["category"]); ?></span>
                <span class="productPages"><?php echo htmlspecialchars($product["pageCount"]); ?> pg</span>
            </div>

            <p class="productPrice">$<?php echo number_format($product["price"], 2); ?></p>
        </div>
    </a>
</article>