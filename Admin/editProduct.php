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

// Gets product id from url
$productId = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($productId === 0) {
    header("Location: manageProducts.php");
    exit();
}

//Database connection
$database = new Database();
$pdo = $database->getConnection();
$productModel = new Product($pdo);

// Loads products existing data
try {
    $existingProduct = $productModel->getProduct($productId);

    if (!$existingProduct) {
        header("Location: manageProducts.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Load product error: " . $e->getMessage());
    header("Location: manageProducts.php");
    exit();
}

$pageTitle = "Edit book";
$pageDescription = "Rewrite the pages";

// Pre fills form variables with existing data
$name = $existingProduct["name"];
$author = $existingProduct["author"];
$description = $existingProduct["description"] ?? "";
$price = $existingProduct["price"];
$image = $existingProduct["image"] ?? "";
$category = $existingProduct["category"] ?? "";
$pageCount = $existingProduct["pageCount"] ?? "";
$stock = $existingProduct["stock"];
$errors = [];
$success = "";

// Edit form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and sanitize input
    $name = trim($_POST["name"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $pageCount = trim($_POST["pageCount"] ?? "");
    $stock = trim($_POST["stock"] ?? "");

    // Keeps existing image
    $newImage = $image;

    //Handle image upload for cover
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $uploadDir = "../Uploads/ProductImg/";

        // Create directory if none exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "webp"];

        if (in_array($imageFileType, $allowedTypes)) {
            if ($_FILES["image"]["size"] <= 15000000) { //15MB

                //Create a unique filename
                $newFileName = uniqid() . "_" . basename($_FILES["image"]["name"]);
                $uniquePath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $uniquePath)) {
                    $newImage = "Uploads/ProductImg/" . $newFileName; //relative path

                    // Delete old image if it exists and is not placeholder
                    if (!empty($image) && file_exists("../" . $image)) {
                        unlink("../" . $image);
                    }
                } else {
                    $errors[] = "Failed to upload image";
                }
            } else {
                $errors[] = "Image too large 15MB max";
            }
        } else {
            $errors[] = "Invalid image type JPG, JPEG, PNG or WEBP only";
        }
    }

    //Validation
    if (empty($name)) {
        $errors[] = "Book name is required";
    } elseif (strlen($name) > 200) {
        $errors[] = "Book name is too long 200 characters or less";
    }

    if (empty($author)) {
        $errors[] = "Author is required";
    } elseif (strlen($author) > 255) {
        $errors[] = " Name is too long 255 characters or less";
    }

    if (empty($price)) {
        $errors[] = "Price is required";
    } elseif (!is_numeric($price) || $price < 0) {
        $errors[] = "Price must more than $0";
    }

    if (!empty($pageCount) && (!is_numeric($pageCount) || $pageCount < 0)) {
        $errors[] = "Page count must more than 0";
    }

    if (empty($category)) {
        $errors[] = "Must choose at least one genre";
    }

    if ($stock === "") {
        $errors[] = "Stock amount is required";
    } elseif (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stock must be 0 or more";
    }

    // Update database if no errors
    if (empty($errors)) {
        try {
            $productModel->updateProduct(
                $productId,
                $name,
                $author,
                $description,
                $price,
                $newImage,
                $category,
                $pageCount,
                $stock
            );

            $success = "Book added successfully";

            // Redirect back to manage product
            header("location: manageProducts.php?success=updated");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Could not update product. Please try again";
            error_log("Update product error: " . $e->getMessage());
        }
    }
}

require_once "../Templates/Header.php";?>

<section class="pageHeader">
    <h1>Edit book</h1>
</section>

<main class="Container">

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
        <div class="errorMessages">
            <?php foreach ($errors as $error): ?>
                <p class="errorMessage"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="addFormContainer">
        <form method="POST" action="editProduct.php?id=<?php echo $productId; ?>" enctype="multipart/form-data" class="addBookForm">

            <div class="formRow">
                <div class="formField">
                    <label for="name">Book title<span class="Required">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           maxlength="200"
                           value="<?php echo htmlspecialchars($name); ?>">
                </div>

                <div class="formField">
                    <label for="author">Author<span class="Required">*</span></label>
                    <input type="text"
                           id="author"
                           name="author"
                           required
                           maxlength="255"
                           value="<?php echo htmlspecialchars($author); ?>">
                </div>
            </div>

            <div class="formField">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          rows="6"><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="formRow">
                <div class="formField">
                    <label for="price">Price<span class="Required">*</span></label>
                    <input type="number"
                           id="price"
                           name="price"
                           step=".01"
                           min="0"
                           required
                           value="<?php echo htmlspecialchars($price); ?>">
                </div>

                <div class="formField">
                    <label for="stock">Stock<span class="Required">*</span></label>
                    <input type="number"
                           id="stock"
                           name="stock"
                           min="0"
                           required
                           value="<?php echo htmlspecialchars($stock); ?>">
                </div>
            </div>

            <div class="formRow">
                <div class="formField">
                    <label for="category">Genre<span class="Required">*</span></label>
                    <input type="text"
                           id="category"
                           name="category"
                           required
                           value="<?php echo htmlspecialchars($category); ?>">
                </div>

                <div class="formField">
                    <label for="pageCount">Page Count</label>
                    <input type="number"
                           id="pageCount"
                           name="pageCount"
                           min="0"
                           value="<?php echo htmlspecialchars($pageCount); ?>">
                </div>
            </div>

            <div class="formField">
                <label for="image">Book Cover image</label>

                <!-- Shows current book image -->
                <?php if (!empty($image)): ?>
                    <div class="currentImage">
                        <p>Current Cover:</p>
                        <img src="../<?php echo htmlspecialchars($image); ?>" alt="Current cover">
                    </div>
                <?php endif; ?>

                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*">
                <small>JPG, PNG, WEBP (Max 15MB)</small>
            </div>

            <div class="formActions">
                <button type="submit" class="submitBtn">Update Book</button>
                <a href="manageProducts.php" class="cancelBtn"> Cancel</a>
            </div>

        </form>
    </div>
</main>

<?php require_once "../Templates/Footer.php"; ?>