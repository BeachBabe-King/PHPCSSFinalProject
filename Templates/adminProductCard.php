<article class="productCard">
    <!-- Product image -->
    <div class="productImageContainer">
        <?php if (!empty($product["image"])): ?>
            <img src="../<?php echo htmlspecialchars($product["image"]); ?>"
                 alt="<?php echo htmlspecialchars($product["name"]); ?>"
                 class="productImage">
        <?php else: ?>
            <img src="../Assets/Images/bookCoverPlaceholder.png"
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
        <p class="productStock">Qty: <?php echo $product["stock"]; ?></p>
    </div>

    <!-- Edit and delete buttons -->
    <div class="manageActions">
        <a href="editProduct.php?id=<?php echo htmlspecialchars($product["id"]); ?>" class="editBtn">Edit</a>

        <!-- Builds delete URL -->
        <?php
        $deleteQuery = [
            "action" => "delete",
            "id" => $product["id"],
            "page" => $currentPage
        ];
        if (!empty($searchQuery)) {
            $deleteQuery["search"] = $searchQuery;
        }
        ?>
        <a href="manageProducts.php?<?php echo http_build_query($deleteQuery); ?>"
           class="deleteBtn"
           onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($product["name"])); ?>?');">Delete</a>
    </div>
</article>
