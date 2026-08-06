/**
 * Welcome page specific JavaScript
 * Bundles Alpine.js and AOS for the landing page via Vite.
 */

// AOS (Animate on Scroll) — bundled instead of CDN
import AOS from 'aos';
import 'aos/dist/aos.css';

// Initialize AOS when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });
});
