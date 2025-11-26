<?php
// Sets base filepath if in admin folder
$inAdmin = strpos($_SERVER['PHP_SELF'], "/Admin/") !== false;
$basePath = $inAdmin ? "../" : "";
?>

<!-- Footer template -->
<footer>
    <!-- Left footer content container -->
    <div class="footContainer">
        <!-- List columns -->
        <div class="footColumn">
            <!-- Contents lists (temporarily blank) -->
            <ul class="footColumnList">
                <li><a href="#">Shipping & Returns</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Find a Store</a></li>
                <li><a href="#">Provide Feedback</a></li>
                <li><a href="#">Careers</a></li>
            </ul>
        </div>
        <div class="footColumn">
            <ul class="footColumnList">
                <li><a href="#">Monthly Reading Challenge</a></li>
                <li><a href="#">Donations</a></li>
                <li><a href="#">Recycling Program</a></li>
                <li><a href="#">Accessibility</a></li>
            </ul>
        </div>
        <div class="footColumn">
            <ul class="footColumnList">
                <li><a href="#">Publishers</a></li>
                <li><a href="#">Authors</a></li>
                <li><a href="#">Recalls</a></li>
                <li><a href="#">Become a Vendor</a></li>
            </ul>
        </div>
    </div>
    <!-- Right side footer container -->
    <div class="footContainer">
        <h3>Join Infinite Rewards Club</h3>
        <p>Collect rewards on every purchase and redeem on book bucks, shipping, and more</p>
        <a class="button" href="#">Learn More</a>
        <div class="socMediaLinks">
            <a href="#">
                <img src="<?php echo $basePath; ?>Assets/Icons/facebook.png" alt="Facebook Logo" class="Icon"></a>
            <a href="#">
                <img src="<?php echo $basePath; ?>Assets/Icons/instagram.png" alt="Instagram Logo" class="Icon"></a>
            <a href="#">
                <img src="<?php echo $basePath; ?>Assets/Icons/twitter.png" alt="X Logo" class="Icon"></a>
        </div>
    </div>
    <div class="footerBottom">
        <p>&copy; <?php echo date('Y'); ?> The Infinite Library. All Rights Reserved </p>
        <a href="#">Privacy Policy</a>
        <a href="#">Preferences</a>
        <a href="#">Terms of Use</a>
    </div>
</footer>
<script src="<?php echo $basePath; ?>js/carousel.js" defer></script>
</body>
</html>