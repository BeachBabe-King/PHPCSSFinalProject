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

$pageTitle = "Add Product";
$pageDescription = "Add a book to the shelves";

//Form variables
$name = "";
$author = "";
$description = "";
$price = "";
$image = "";
$category = "";
$pageCount = "";
$stock = "";
$errors = [];
$success = "";

//Database connection
$database = new Database();
$pdo = $database->getConnection();
$productModel = new Product($pdo);

// add form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and sanitize input
    $name = trim($_POST["name"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $pageCount = trim($_POST["pageCount"] ?? "");
    $stock = trim($_POST["stock"] ?? "");

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
                    $image = "Uploads/ProductImg/" . $newFileName; //relative path
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

    if (empty($stock)) {
        $errors[] = "Stock amount is required";
    } elseif (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stock must be 0 or more";
    }

    // Add to database if no errors
    if (empty($errors)) {
        try {
            $productModel->createProduct(
                $name,
                $author,
                $description,
                $price,
                $image,
                $category,
                $pageCount,
                $stock
            );

            $success = "Book added successfully";

            // Redirect back to add to clear form
            header("location: addProduct.php?success=1");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Could not add product. Please try again";
            error_log("Add product error: " . $e->getMessage());
        }
    }
}

// Checks for success message from redirect
if (isset($_GET["success"]) && $_GET["success"] == 1) {
    $success = "Book added successfully";
}

require_once "../Templates/Header.php";
?>

<section class="pageHeader">
    <h1>Add a book</h1>
</section>

<main class="Container">

    <!-- Success message -->
    <?php if (!empty($success)): ?>
        <div class="successMessage">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
        <div class="errorMessages">
            <?php foreach ($errors as $error): ?>
                <p class="errorMessage"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="addFormContainer">

        <form method="POST" action="addProduct.php" enctype="multipart/form-data" class="addBookForm">

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
                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*">
                <small>JPG, PNG, WEBP (Max 15MB)</small>
            </div>

            <div class="formActions">
                <button type="submit" class="submitBtn">Add Book</button>
                <a href="Dashboard.php" class="cancelBtn"> Cancel</a>
            </div>

        </form>
    </div>
</main>

<?php require_once "../Templates/Footer.php"; ?>