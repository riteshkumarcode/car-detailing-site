import { gsap } from 'gsap';

export function initMarquee() {
    const marquees = document.querySelectorAll('[data-motion="marquee"]');
    if (marquees.length === 0) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    marquees.forEach(marquee => {
        const track = marquee.querySelector('.marquee-track');
        if (!track) return;

        const duration = parseFloat(marquee.getAttribute('data-duration')) || 24;

        const tween = gsap.to(track, {
            xPercent: -50,
            repeat: -1,
            duration: duration,
            ease: 'none',
        });

        // Pause on hover & touch
        marquee.addEventListener('mouseenter', () => tween.pause());
        marquee.addEventListener('mouseleave', () => tween.play());
        marquee.addEventListener('touchstart', () => tween.pause(), { passive: true });
        marquee.addEventListener('touchend', () => tween.play(), { passive: true });
    });
}
