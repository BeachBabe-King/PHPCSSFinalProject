<!-- Login page -->
<?php
require_once "Config/Config.php";
require_once "Classes/Database.php";
require_once "Classes/User.php";

session_start();

//Redirects if already logged in
if (isset($_SESSION["userId"])) {
    if ($_SESSION["isAdmin"]) {
        header("Location: Admin/Dashboard.php");
    } else {
        header("Location: index.php");
    }
    die();
}

$pageTitle = "Login";
$pageDescription = "Login to The Infinite Library";

$errors = [];
$database = new Database();
$pdo = $database->getConnection();
$userModel = new User($pdo);

//Login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    //Validation
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    // User authentication
    if (empty($errors)) {
        try {
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["userId"] = $user["userId"];
                $_SESSION["userName"] = $user["firstName"] . " " . $user["lastName"];
                $_SESSION["userEmail"] = $user["email"];
                $_SESSION["isAdmin"] = (bool)$user["isAdmin"];

                //Redirection based on admin or not
                if ($user["isAdmin"]) {
                    header("Location: Admin/Dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $errors[] = "Wrong email or password";
            }
        } catch (PDOException $e) {
            $errors[] = "Login failed. Please try again";
            error_log("Login error: " . $e->getMessage());
        }
    }
}

require_once "Templates/Header.php";
?>

<section class="pageHeader">
    <h1>Login to your account</h1>
</section>

<main class="Container">
    <div class="loginContainer">

        <!-- Error messages -->
        <?php if (!empty($errors)): ?>
            <div class="errorMessages">
                <?php foreach ($errors as $error): ?>
                    <p class="errorMessage"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <button class="closeMsg" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="Login.php" class="loginForm">
            <div class="loginField">
                <label for="email">Email Address</label>
                <input type="email"
                       id="email"
                       name="email"
                       required
                       value="<?php echo htmlspecialchars($_POST["email"] ?? "") ?>">
            </div>

            <div class="loginField">
                <label for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       required>
            </div>
            <button type="submit" class="submitBtn">Login</button>
        </form>

        <div class="registerLink">
            <p>No account? <a href="Register.php">Register here</a></p>
        </div>
    </div>

</main>

<?php require_once "Templates/Footer.php"; ?>