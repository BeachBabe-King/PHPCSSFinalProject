// Wait until page fully loads before running
document.addEventListener("DOMContentLoaded", () => {

    // Select all carousels on page
    const carousels = document.querySelectorAll(".carouselWrapper");

    // Apply functionality to all carousels
    carousels.forEach(carousel => {

        // Scroll and nav buttons
        const track = carousel.querySelector(".carouselTrack");
        const prevBtn = carousel.querySelector(".carouselBtnPrev");
        const nextBtn = carousel.querySelector(".carouselBtnNext");

        // Get product cards in track
        const cards = track.querySelectorAll(".carouselCard");

        if (!cards.length) return;

        // Sets width and gap by fist card
        const card = cards[0];
        const cardStyles = window.getComputedStyle(card);
        const gap = parseInt(cardStyles.marginRight);

        // How many cards fit in view, and scrolls
        function getScrollAmount() {
            const visibleCards = Math.floor(track.offsetWidth / card.offsetWidth);
            return visibleCards * (card.offsetWidth + gap);
        }

        // Disable buttons when at ends
        function updateButtons() {
            prevBtn.disabled = track.scrollLeft <= 0;
            nextBtn.disabled =
                track.scrollLeft + track.offsetWidth >= track.scrollWidth - 1;
        }

        // Scroll carousel left
        prevBtn.addEventListener("click", () => {
            track.scrollBy({
                left: -getScrollAmount(),
                behavior: "smooth"
            });
        });

        // Scroll carousel right
        nextBtn.addEventListener("click", () => {
            track.scrollBy({
                left: getScrollAmount(),
                behavior: "smooth"
            });
        });

        // Update button states while scrolling
        track.addEventListener("scroll", updateButtons);

        // Set initial button states
        updateButtons();
    });
});