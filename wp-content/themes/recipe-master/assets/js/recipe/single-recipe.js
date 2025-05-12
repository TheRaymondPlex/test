/**
 * Recipe Single JavaScript
 */
(function() {
    'use strict';
    
    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initRatingStars();
    });

    /**
     * Initialize rating stars display
     */
    function initRatingStars() {
        const ratingContainers = document.querySelectorAll('[data-rating]');
        
        ratingContainers.forEach(function(container) {
            const ratingValue = parseFloat(container.dataset.rating) || 0;
            const starsContainer = container.querySelector('.recipe-stars');
            
            if (!starsContainer) return;
            
            // Clear any existing stars
            starsContainer.innerHTML = '';
            
            // Generate 5 stars
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('span');
                
                if (i <= Math.floor(ratingValue)) {
                    // Full star (yellow)
                    star.className = 'star';
                    star.innerHTML = getStarSvg(true);
                } else {
                    // Empty star (white)
                    star.className = 'star star-empty';
                    star.innerHTML = getStarSvg(false);
                }
                
                starsContainer.appendChild(star);
            }
        });
    }

    /**
     * Get star SVG markup
     * 
     * @param {boolean} filled Whether the star should be filled
     * @return {string} SVG markup
     */
    function getStarSvg(filled) {
        if (filled) {
            return '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"></path></svg>';
        } else {
            return '<svg class="w-5 h-5" fill="white" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"></path></svg>';
        }
    }
})(); 