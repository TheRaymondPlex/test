/**
 * Purple theme main JavaScript file
 */

document.addEventListener('DOMContentLoaded', () => {
    updateProgress();
    smoothAnchorScroll();
    setupExpertsCounter();
});

/**
 * Handle smooth scrolling for anchor links
 */
const smoothAnchorScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
}

/**
 * Update progress bar based on scroll position
 */
const updateProgress = () => {
    const container = document.querySelector('.container');
    const sections = document.querySelectorAll('section');
    const progressBar = document.querySelector('.progress-bar');

    const bgImage1 = document.querySelector('.bg-image-1');
    const bgImage2 = document.querySelector('.bg-image-2');
    const bgImage3 = document.querySelector('.bg-image-3');

    const scrollPosition = container.scrollTop;
    const windowHeight = window.innerHeight;

    let currentSection = Math.round(scrollPosition / windowHeight);

    const progress = (currentSection + 1) * 20;

    progressBar.style.width = `${progress}%`;

    if (currentSection <= 1) {
        bgImage1.classList.remove('hidden')
        bgImage2.classList.remove('hidden')
        bgImage3.classList.remove('hidden')
    }
    if (currentSection === 2) {
        bgImage1.classList.add('hidden')
        bgImage2.classList.add('hidden')
        bgImage3.classList.remove('hidden')
    }

    if (currentSection > 2) {
        bgImage1.classList.add('hidden')
        bgImage2.classList.add('hidden')
        bgImage3.classList.add('hidden')
    }

    container.addEventListener('scroll', updateProgress);
}

/**
 * Setup counter for experts number
 */
const setupExpertsCounter = () => {
    const expertsNumberContainer = document.querySelector('.experts-number');
    if (!expertsNumberContainer) return;
    
    const decreaseButton = expertsNumberContainer.querySelector('.experts-number-button:first-child');
    const increaseButton = expertsNumberContainer.querySelector('.experts-number-button:last-child');
    const countDisplay = expertsNumberContainer.querySelector('span');

    let count = 1;
    const minCount = 1;

    // Update the display
    function updateDisplay() {
        countDisplay.textContent = count;
    }

    decreaseButton.addEventListener('click', () => {
        if (count > minCount) {
            count--;
            updateDisplay();
        }
    });

    increaseButton.addEventListener('click', () => {
        count++;
        updateDisplay();
    });
} 