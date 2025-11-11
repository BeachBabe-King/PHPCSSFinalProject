<!-- Company help page -->
<?php
$pageTitle = "Infinite Library Support & Help";
$pageDescription = "Get help with your order, find answers to common questions, and contact our team.";

require_once("Templates/Header.php");
?>

<!-- page header -->
<section class="pageHeader">
    <div class="Container">
        <h1>How Can We Help?</h1>
        <p>For a better experience</p>
    </div>
</section>

<!-- Main content -->
<main class="Container">

    <!-- Help topics -->
    <section class="helpBoxes">
        <a href="#" class="helpBox">
            <h3>Order Status</h3>
            <p>Track your order and view delivery updates</p>
        </a>

        <a href="#" class="helpBox">
            <h3>Shipping & delivery</h3>
            <p>info about our shipping and delivery options</p>
        </a>

        <a href="#" class="helpBox">
            <h3>Returns & Exchanges</h3>
            <p>How to get a refund or exchange you book</p>
        </a>

        <a href="#" class="helpBox">
            <h3>Account Help</h3>
            <p>Account problem troubleshooting</p>
        </a>

        <a href="#" class="helpBox">
            <h3>Payment & Billing</h3>
            <p>Questions about payment methods & billing</p>
        </a>

        <a href="#" class="helpBox">
            <h3>FAQs</h3>
            <p>Answers to frequently asked questions</p>
        </a>
    </section>

    <!-- Company contact info -->
    <section id="contactInfo">
        <h2>Contact The Infinite Library</h2>

        <div class="contactInfo">

        <!-- Phone contact -->Container
            <div class="contactInfoCard">
                <img class="contactIcon" src="Assets/Icons/redPhone.svg" alt="Phone Icon">
                <h3>Phone</h3>
                <p class="contactDetails">
                    <a href="#">1-800-555-BOOK (2665)</a>
                </p>
                <p class="contactHours">Monday - Friday: 9:00 AM - 6:00 PM EST Saturday: 10:00 AM - 4:00 PM EST
                </p>
            </div>

            <!-- Email -->
            <div class="contactInfoCard">
                <img class="contactIcon" src="Assets/Icons/envelopeIcon.svg" alt="Mail Icon">
                <h3>Email</h3>
                <p class="contactDetails">
                    <a href="#">support@infinitelibrary.ca</a>
                </p>
                <p class="contactHours">
                    Responses within 1-2 business days
                </p>
            </div>

            <!-- Live chat -->
            <div class="contactInfoCard">
                <img class="contactIcon" src="Assets/Icons/speachBubbleIcon.svg" alt="Chat Bubble">
                <h3>Live Chat</h3>
                <p class="contactDetails">
                    <a href="#">Start a chat</a>
                </p>
                <p class="contactHours">
                    Monday - Friday: 9:00 AM - 9:00 PM EST Saturday - Sunday: 10:00 AM - 6:00 PM EST
                </p>
            </div>

            <!-- Mailing -->
            <div class="contactInfoCard">
                <img class="contactIcon" src="Assets/Icons/locationPin.svg" alt="Location Icon">
                <h3>Mailing Address</h3>
                <p class="contactDetails">
                    The Infinite Library
                    123 Book Street
                    Barrie, ON L4C 5P3
                    Canada
                </p>
            </div>
        </div>
    </section>
</main>

<?php
require_once("Templates/Footer.php");
?>