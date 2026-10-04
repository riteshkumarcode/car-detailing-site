import Alpine from 'alpinejs';
import { initMotion } from './motion';

window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Initialize GSAP Motion & Diagnostic Scan System
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initMotion());
} else {
    initMotion();
}

// PWA Service Worker Registration
if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(error => {
            console.log('ServiceWorker registration failed:', error);
        });
    });
}
