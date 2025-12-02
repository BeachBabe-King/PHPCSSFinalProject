<?php
require_once "Config/Config.php";
require_once "Classes/Database.php";
require_once "Classes/User.php";

session_start();

if (isset($_SESSION["userId"])) {
    if ($_SESSION["isAdmin"]) {
        header("Location: Admin/Dashboard.php");
    } else {
        header("Location: index.php");
    }
    die();
}

$pageTitle = "Create Account";
$pageDescription = "Register an Infinite Library account";

// Form data variables
$firstName = "";
$lastName = "";
$email = "";
$phone = "";
$address = "";
$password = "";
$confirmPassword = "";
$errors = [];

$database = new Database();
$pdo = $database->getConnection();
$userModel = new User($pdo);

// Registration form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Get and sanitize input
    $firstName = trim($_POST["firstName"] ?? "");
    $lastName = trim($_POST["lastName"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";

    // Validation
    if (empty($firstName)) {
        $errors[] = "First Name is required";
    } elseif (strlen($firstName) > 50) {
        $errors[] = "First Name must be 50 characters or less";
    }

    if (empty($lastName)) {
        $errors[] = "Last Name is required";
    } elseif (strlen($lastName) > 50) {
        $errors[] = "Last Name must be 50 characters or less";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    } elseif (strlen($email) > 125) {
        $errors[] = "Email must be 125 characters or less";
    }

    if (!empty($phone) && strlen($phone) > 15) {
        $errors[] = "Phone must be 15 characters or less";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be more than 8 characters";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    //Check if email already exists
    if (empty($errors)) {
        try {
            if ($userModel->emailExists($email)) {
                $errors[] = "An account with this email already exists";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error. Please try again later";
            error_log("Email check error: " . $e->getMessage());
        }
    }

    //Create account if no errors
    if (empty($errors)) {
        try {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $newUserId = $userModel->create(
                $firstName,
                $lastName,
                $email,
                $hashedPassword,
                $phone ?: null,
                $address ?: null
            );
            // Logs in right away if successful
            $_SESSION["userId"] = $newUserId;
            $_SESSION["userName"] = $firstName . " " . $lastName;
            $_SESSION["userEmail"] = $email;
            $_SESSION["isAdmin"] = false;

            //Redirect to homepage
            header("Location: index.php?success=registered");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Could not create account. Please try again later";
            error_log("Registration error: " . $e->getMessage());
        }
    }
}

require_once "Templates/Header.php";
?>

<section class="pageHeader">
    <h1>Create Your Account</h1>
</section>

<main class="Container">
    <div class="registerCard">
        <div class="cardBody">
            <h2 class="cardTitle">Create Your Profile</h2>

            <!-- Error messages -->
            <?php if (!empty($errors)): ?>
                <div class="errorMessages">
                    <?php foreach ($errors as $error): ?>
                        <p class="errorMessage"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                    <button class="closeMsg" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Registration form -->
            <form method="POST" action="Register.php" class="registerForm">
                <div class="formRow">
                    <div class="formField">
                        <label for="firstName">First Name <span class="Required">*</span></label>
                        <input type="text"
                               id="firstName"
                               name="firstName"
                               required
                               maxlength="50"
                               value="<?php echo htmlspecialchars($firstName); ?>">
                    </div>

                    <div class="formField">
                        <label for="lastName">Last Name<span class="Required">*</span></label>
                        <input type="text"
                               id="lastName"
                               name="lastName"
                               required
                               maxlength="50"
                               value="<?php echo htmlspecialchars($lastName); ?>">
                    </div>
                </div>

                <div class="formField">
                    <label for="email">Email Address <span class="Required">*</span></label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           maxlength="125"
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="formField">
                    <label for="phone">Phone Number</label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           maxlength="15"
                           value="<?php echo htmlspecialchars($phone); ?>">
                </div>

                <div class="formField">
                    <label for="address">Address</label>
                    <textarea id="address"
                              name="address"
                              rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                </div>

                <div class="formField">
                    <label for="password">Password <span class="Required">*</span></label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           minlength="8">
                </div>

                <div class="formField">
                    <label for="confirmPassword">Confirm Password <span class="Required">*</span></label>
                    <input type="password"
                           id="confirmPassword"
                           name="confirmPassword"
                           required
                           minlength="8">
                </div>

                <button type="submit" class="submitBtn">Create Account</button>
            </form>

            <div class="loginLink">
                <p>Already have an account? <a href="Login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "Templates/Footer.php"; ?>

