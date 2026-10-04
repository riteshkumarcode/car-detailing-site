import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initBeforeAfterSliders() {
    const sliders = document.querySelectorAll('[data-motion="before-after-slider"]');
    if (sliders.length === 0) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    sliders.forEach(slider => {
        // Find the Alpine data scope or container
        ScrollTrigger.create({
            trigger: slider,
            start: 'top 75%',
            once: true,
            onEnter: () => {
                // If Alpine is present on this element, do a subtle intro sweep to teach interactivity
                const alpineData = window.Alpine?.$data(slider);
                if (alpineData && typeof alpineData.sliderPos !== 'undefined') {
                    const obj = { pos: 50 };
                    gsap.timeline({ defaults: { ease: 'power2.inOut' } })
                        .to(obj, {
                            pos: 35,
                            duration: 0.6,
                            onUpdate: () => { alpineData.sliderPos = Math.round(obj.pos); }
                        })
                        .to(obj, {
                            pos: 65,
                            duration: 0.8,
                            onUpdate: () => { alpineData.sliderPos = Math.round(obj.pos); }
                        })
                        .to(obj, {
                            pos: 50,
                            duration: 0.6,
                            onUpdate: () => { alpineData.sliderPos = Math.round(obj.pos); }
                        });
                }
            }
        });
    });
}
