import { gsap } from 'gsap';

export function initHeroScan() {
    const hero = document.querySelector('[data-motion="hero"]');
    if (!hero) return;

    // Check for reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // 1. Headline and copy entrance
    const locationPill = hero.querySelector('.inline-flex');
    const headline = hero.querySelector('h1');
    const leadText = hero.querySelector('p');
    const ctaButtons = hero.querySelector('.flex.flex-wrap.items-center.gap-4');
    const factsGrid = hero.querySelector('.grid.grid-cols-3');

    if (headline) {
        tl.from(locationPill, { y: 16, opacity: 0, duration: 0.5 }, 0)
          .from(headline, { y: 32, opacity: 0, duration: 0.8 }, 0.1)
          .from(leadText, { y: 24, opacity: 0, duration: 0.7 }, 0.25)
          .from(ctaButtons, { y: 20, opacity: 0, duration: 0.6 }, 0.4)
          .from(factsGrid, { y: 20, opacity: 0, duration: 0.6 }, 0.5);
    }

    // 2. Diagnostic Card Panel Entrance
    const diagCard = hero.querySelector('.card-panel');
    if (diagCard) {
        tl.from(diagCard, {
            y: 40,
            opacity: 0,
            scale: 0.97,
            duration: 0.9,
            ease: 'back.out(1.4)'
        }, 0.2);
    }

    // 3. Top-down Car Scan Laser Animation
    const scanLine = hero.querySelector('[data-motion="scan-line"]');
    if (scanLine) {
        // Initial sweep
        tl.fromTo(scanLine, 
            { attr: { y1: 30, y2: 30 }, opacity: 0 },
            { attr: { y1: 370, y2: 370 }, opacity: 1, duration: 1.4, ease: 'power1.inOut' },
            0.6
        ).to(scanLine, { opacity: 0.4, duration: 0.3 });

        // Continuous subtle ambient laser sweep loop
        gsap.to(scanLine, {
            attr: { y1: 370, y2: 370 },
            repeat: -1,
            yoyo: true,
            duration: 3.5,
            ease: 'sine.inOut',
            delay: 2.2
        });
    }

    // 4. Numbered Defect Markers Pop in
    const markers = hero.querySelectorAll('[data-motion="marker-spot"]');
    if (markers.length > 0) {
        tl.from(markers, {
            scale: 0,
            opacity: 0,
            stagger: 0.18,
            duration: 0.6,
            ease: 'back.out(2.2)'
        }, 1.2);
    }

    // 5. Drive Health Score Counter (0 -> target score) & Arc Fill
    const scoreNum = hero.querySelector('[data-motion="score-number"]');
    const scoreArc = hero.querySelector('[data-motion="score-arc"]');

    if (scoreNum) {
        const targetScore = parseInt(scoreNum.innerText.trim(), 10) || 72;
        const countObj = { val: 0 };

        tl.to(countObj, {
            val: targetScore,
            duration: 1.8,
            ease: 'power2.out',
            onUpdate: () => {
                scoreNum.innerText = Math.round(countObj.val);
            }
        }, 0.8);

        if (scoreArc) {
            const radius = 70;
            const semiCircumference = Math.PI * radius; // ~219.91
            const targetOffset = semiCircumference - (targetScore / 100) * semiCircumference;

            gsap.set(scoreArc, { strokeDashoffset: semiCircumference });

            tl.to(scoreArc, {
                strokeDashoffset: targetOffset,
                duration: 1.8,
                ease: 'power2.out'
            }, 0.8);
        }
    }

    // 6. Floating badges subtle idle bobbing
    const calloutBadges = hero.querySelectorAll('.xl\\:flex');
    if (calloutBadges.length > 0) {
        tl.from(calloutBadges, {
            scale: 0.8,
            opacity: 0,
            stagger: 0.2,
            duration: 0.6,
            ease: 'back.out(1.7)'
        }, 1.4);

        calloutBadges.forEach((badge, i) => {
            gsap.to(badge, {
                y: i % 2 === 0 ? '-=5' : '+=5',
                repeat: -1,
                yoyo: true,
                duration: 2.8 + i * 0.4,
                ease: 'sine.inOut',
                delay: 2.0 + i * 0.2
            });
        });
    }
}
