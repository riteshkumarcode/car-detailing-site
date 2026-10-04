import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initScrollReveals() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    // 1. Reveal Section Titles & Headings
    const sectionHeadings = document.querySelectorAll('[data-motion="section-heading"]');
    sectionHeadings.forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                once: true,
            },
            y: 28,
            opacity: 0,
            duration: 0.7,
            ease: 'power2.out',
        });
    });

    // 2. Batch Reveal Cards (Services, Drive Club, Features, FAQs)
    const cardContainers = document.querySelectorAll('[data-motion="card-grid"]');
    cardContainers.forEach(container => {
        const cards = container.querySelectorAll('.card-panel, .group, > div');
        if (cards.length > 0) {
            ScrollTrigger.batch(cards, {
                start: 'top 85%',
                once: true,
                onEnter: batch => {
                    gsap.from(batch, {
                        y: 32,
                        opacity: 0,
                        stagger: 0.12,
                        duration: 0.7,
                        ease: 'power2.out',
                        overwrite: 'auto',
                    });
                }
            });
        }
    });

    // 3. Numbered Step Cards in "How a Visit Works"
    const stepCards = document.querySelectorAll('[data-motion="step-card"]');
    if (stepCards.length > 0) {
        ScrollTrigger.batch(stepCards, {
            start: 'top 85%',
            once: true,
            onEnter: batch => {
                gsap.from(batch, {
                    y: 36,
                    opacity: 0,
                    stagger: 0.15,
                    duration: 0.8,
                    ease: 'back.out(1.4)',
                });
            }
        });
    }

    // 4. Subtle Lift Micro-interactions on buttons
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            gsap.to(btn, { y: -2, duration: 0.2, ease: 'power1.out' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { y: 0, duration: 0.2, ease: 'power1.out' });
        });
        btn.addEventListener('mousedown', () => {
            gsap.to(btn, { scale: 0.98, duration: 0.1, ease: 'power1.out' });
        });
        btn.addEventListener('mouseup', () => {
            gsap.to(btn, { scale: 1, duration: 0.1, ease: 'power1.out' });
        });
    });
}
