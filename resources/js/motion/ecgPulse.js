import { gsap } from 'gsap';

export function initEcgPulse() {
    const ecgLines = document.querySelectorAll('[data-motion="ecg-pulse"]');
    if (ecgLines.length === 0) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    ecgLines.forEach(container => {
        const pulseDot = container.querySelector('.ecg-pulse-dot');
        const pulsePath = container.querySelector('.ecg-pulse-path');

        if (pulseDot) {
            gsap.to(pulseDot, {
                x: '100%',
                repeat: -1,
                duration: 4.5,
                ease: 'none',
            });
        }

        if (pulsePath) {
            const length = pulsePath.getTotalLength ? pulsePath.getTotalLength() : 800;
            gsap.set(pulsePath, {
                strokeDasharray: length,
                strokeDashoffset: length,
            });

            gsap.to(pulsePath, {
                strokeDashoffset: 0,
                repeat: -1,
                duration: 5,
                ease: 'linear',
            });
        }
    });
}
