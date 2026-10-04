import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from '@studio-freight/lenis';
import { initHeroScan } from './heroScan';
import { initEcgPulse } from './ecgPulse';
import { initMarquee } from './marquee';
import { initBeforeAfterSliders } from './beforeAfterSlider';
import { initScrollReveals } from './scrollReveals';

gsap.registerPlugin(ScrollTrigger);

export function initMotion() {
    // 1. Accessibility & Performance Checks
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isLowPower = navigator.connection?.saveData || (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2);

    // 2. Desktop Smooth Scrolling via Lenis (Disabled on mobile/touch & low power for optimal battery & 60fps)
    const isDesktop = window.innerWidth >= 1024 && !('ontouchstart' in window);
    let lenis = null;

    if (isDesktop && !prefersReducedMotion && !isLowPower) {
        lenis = new Lenis({
            duration: 1.1,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            smoothWheel: true,
            wheelMultiplier: 1.0,
            touchMultiplier: 2.0,
        });

        lenis.on('scroll', ScrollTrigger.update);

        gsap.ticker.add((time) => {
            lenis.raf(time * 1000);
        });

        gsap.ticker.lagSmoothing(0);
    }

    // 3. Initialize Motion Modules
    initHeroScan();
    initEcgPulse();
    initMarquee();
    initBeforeAfterSliders();
    initScrollReveals();

    // 4. Refresh ScrollTrigger after DOM load / Livewire navigation
    window.addEventListener('load', () => {
        ScrollTrigger.refresh();
    });

    document.addEventListener('livewire:navigated', () => {
        ScrollTrigger.refresh();
    });
}
