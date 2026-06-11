/**
 * Hilir Migas Landing Page - JavaScript Enhancements
 * 
 * This file contains additional JavaScript functionality for the landing page.
 * Include this file before closing body tag or after Alpine.js initialization.
 * 
 * Usage: <script src="{{ asset('js/landing-custom.js') }}"></script>
 */

(function() {
    'use strict';

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================

    /**
     * Smooth scroll to element
     */
    function smoothScrollTo(target) {
        const element = document.querySelector(target);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    /**
     * Get scroll position
     */
    function getScrollPosition() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }

    /**
     * Check if element is in viewport
     */
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.bottom >= 0
        );
    }

    // ============================================
    // SCROLL EFFECTS
    // ============================================

    /**
     * Navbar scroll effect
     */
    function initNavbarScroll() {
        const navbar = document.querySelector('nav');
        if (!navbar) return;

        window.addEventListener('scroll', () => {
            const scrollPos = getScrollPosition();
            if (scrollPos > 50) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
    }

    /**
     * Back to top button
     */
    function initBackToTopButton() {
        const backToTopBtn = document.querySelector('button[onclick*="scrollTo"]');
        if (!backToTopBtn) return;

        window.addEventListener('scroll', () => {
            const scrollPos = getScrollPosition();
            if (scrollPos > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
    }

    // ============================================
    // COUNTER ANIMATION
    // ============================================

    /**
     * Animate counter numbers
     */
    function animateCounter(element, target, duration = 2000) {
        let current = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    /**
     * Initialize counter animations on scroll
     */
    function initCounterAnimation() {
        const counters = document.querySelectorAll('.counter');
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = 'true';
                    const text = entry.target.textContent.trim();
                    const number = parseInt(text);
                    if (!isNaN(number)) {
                        animateCounter(entry.target, number);
                    }
                }
            });
        }, observerOptions);

        counters.forEach(counter => observer.observe(counter));
    }

    // ============================================
    // LAZY LOADING
    // ============================================

    /**
     * Lazy load images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });
            images.forEach(img => imageObserver.observe(img));
        }
    }

    // ============================================
    // FORM HANDLING
    // ============================================

    /**
     * Handle form submissions
     */
    function initFormHandling() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Loading...';
                }
            });
        });
    }

    // ============================================
    // LINK HANDLING
    // ============================================

    /**
     * Handle smooth scroll links
     */
    function initSmoothScrollLinks() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                e.preventDefault();
                smoothScrollTo(href);
            });
        });
    }

    // ============================================
    // MOBILE MENU
    // ============================================

    /**
     * Handle mobile menu close on link click
     */
    function initMobileMenuClose() {
        const mobileLinks = document.querySelectorAll('[x-show*="open"] a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Alpine.js will handle the menu toggle
                // This is just for analytics tracking if needed
            });
        });
    }

    // ============================================
    // EXTERNAL LINK HANDLING
    // ============================================

    /**
     * Open external links in new tab
     */
    function initExternalLinks() {
        document.querySelectorAll('a[target="_blank"]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Add any tracking here if needed
            });
        });
    }

    // ============================================
    // PERFORMANCE MONITORING
    // ============================================

    /**
     * Log page performance metrics
     */
    function logPerformanceMetrics() {
        if (window.performance && window.performance.timing) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const perfData = window.performance.timing;
                    const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                    
                    console.log('Page Load Performance:');
                    console.log('Total Load Time: ' + pageLoadTime + 'ms');
                    
                    if (window.performance.getEntriesByType) {
                        const resources = window.performance.getEntriesByType('resource');
                        console.log('Resources loaded: ' + resources.length);
                    }
                }, 0);
            });
        }
    }

    // ============================================
    // INITIALIZATION
    // ============================================

    /**
     * Initialize all features when DOM is ready
     */
    function init() {
        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAll);
        } else {
            initAll();
        }
    }

    /**
     * Run all initialization functions
     */
    function initAll() {
        try {
            initNavbarScroll();
            initBackToTopButton();
            initCounterAnimation();
            initLazyLoading();
            initFormHandling();
            initSmoothScrollLinks();
            initMobileMenuClose();
            initExternalLinks();
            
            // Only log in development
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                logPerformanceMetrics();
            }
        } catch (error) {
            console.error('Error during Landing Page initialization:', error);
        }
    }

    // ============================================
    // GLOBAL UTILITIES
    // ============================================

    // Expose utilities to global scope if needed
    window.HilirMigasLanding = {
        smoothScrollTo: smoothScrollTo,
        getScrollPosition: getScrollPosition,
        isInViewport: isInViewport,
        animateCounter: animateCounter
    };

    // ============================================
    // START INITIALIZATION
    // ============================================

    init();

})();

// ============================================
// POLYFILLS
// ============================================

// Smooth scroll polyfill for older browsers
if (!('scrollBehavior' in document.documentElement.style)) {
    console.warn('Smooth scroll not supported. Using fallback.');
}

// ============================================
// END OF FILE
// ============================================
