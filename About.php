<!-- About company page -->
<?php
$pageTitle = "About us";
$pageDescription = "The Infinite Library's Story";

require_once("Templates/Header.php"); ?>

<section class="pageHeader">
    <div class="Container">
        <h1>What's Our Story?</h1>
    </div>
</section>

<!-- Main content -->
<main class="Container">

    <!-- Story -->
    <section class="infStory">
        <div class="infStoryText">
            <p>
                The Infinite Library started with a humble belief: that everyone should be able to access the
                transformative power of books. Beginning in 2020, we started as a meagre collection of
                lesser known titles and authors, with the belief that every story someone writes should be read.
            </p>
            <p>
                Over the last few years, our shelves grown exponentially. What started as a few handpicked books
                transformed into a huge collection covering every genre, voice, and era. From timeless classics
                to blooming indie writers, we believe that every book has a reader, and every reader deserves
                the chance to discover something that speaks to them.
            </p>
            <p>
                More than just a place to buy a book. The Infinite Library is a place for discovery,
                learning, and adventure. no matter what you're searching for how to cook, a cozy read, or to get
                lost in a new world, we are here to help you find your next chapter.
            </p>
        </div>
    </section>

    <!-- Mission section -->
    <section id="Mission">
        <div class="sectionHeader">
            <h2>Our Infinite Goal</h2>
            <p>What keeps us going</p>
        </div>

        <!-- Mission cards -->
        <div class="missionCards">
           <article class="missionCard">
               <h3>Your Story Matters</h3>
               <p>We believe that every story should be seen, cherished, and shared.</p>
           </article>

            <article class="missionCard">
                <h3>Claim Your Next Story</h3>
                <p>There's always another story waiting for you here.</p>
            </article>

            <article class="missionCard">
                <h3>A Place For Everyone</h3>
                <p>Our shelves are filled with voices from every corner of the world,so every
                    reader can find a story that feels like home.</p>
            </article>
        </div>
    </section>
</main>

<?php
require_once("Templates/Footer.php");
?>