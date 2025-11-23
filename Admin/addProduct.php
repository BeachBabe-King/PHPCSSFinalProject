<?php
require_once "../Config/Config.php";
require_once "../Classes/Database.php";

session_start();

//check if user is logged in and is admin
if (!isset($_SESSION["userId"]) || !$_SESSION["isAdmin"]) {
    header("Location: ../Login.php");
    exit();
}

$pageTitle = "Add Product";
$pageDescription = "Add a book to the shelves";

