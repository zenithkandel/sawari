/* =====================================================
   Sawari - Landing Page JavaScript
   Loader, Navigation, Theme Toggle, Animations & Parallax
   ===================================================== */

document.addEventListener('DOMContentLoaded', () => {
    initLoader();
    initThemeToggle();
    initNavigation();
    initScrollAnimations();
    initCounters();
    initParallax();
});

/* =====================================================
   Loader
   ===================================================== */
function initLoader() {
    const loader = document.getElementById('loader');

    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.style.overflow = '';
        }, 2000);
    });

    document.body.style.overflow = 'hidden';
}

/* =====================================================
   Theme Toggle
   ===================================================== */
function initThemeToggle() {
    const toggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    // Check for saved theme preference or system preference
    const savedTheme = localStorage.getItem('sawari-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme) {
        html.setAttribute('data-theme', savedTheme);
    } else if (!systemPrefersDark) {
        html.setAttribute('data-theme', 'light');
    }

    toggle.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('sawari-theme', newTheme);

        // Add rotation animation to toggle
        toggle.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            toggle.style.transform = '';
        }, 300);
    });

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('sawari-theme')) {
            html.setAttribute('data-theme', e.matches ? 'dark' : 'light');
        }
    });
}

/* =====================================================
   Navigation
   ===================================================== */
function initNavigation() {
    const nav = document.getElementById('nav');
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    // Scroll behavior
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;

        if (currentScroll > 100) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    }, { passive: true });

    // Mobile toggle
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                const offset = 72; // nav height
                const position = target.getBoundingClientRect().top + window.scrollY - offset;

                window.scrollTo({
                    top: position,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                navToggle?.classList.remove('active');
                navLinks?.classList.remove('active');
            }
        });
    });
}

/* =====================================================
   Scroll Animations with GSAP
   ===================================================== */
function initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('GSAP not loaded, using fallback animations');
        initFallbackAnimations();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    // Section headers
    gsap.utils.toArray('.section-header').forEach(header => {
        gsap.from(header.children, {
            scrollTrigger: {
                trigger: header,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.15,
            ease: 'power3.out'
        });
    });

    // Problem stats
    gsap.utils.toArray('.problem-stat').forEach((stat, i) => {
        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            },
            x: -40,
            opacity: 0,
            duration: 0.6,
            delay: i * 0.1,
            ease: 'power3.out'
        });
    });

    // Flow steps
    gsap.utils.toArray('.flow-step').forEach((step, i) => {
        gsap.from(step, {
            scrollTrigger: {
                trigger: step,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            y: 50,
            opacity: 0,
            duration: 0.7,
            ease: 'power3.out'
        });
    });

    // Feature cards
    gsap.utils.toArray('.feature-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            },
            y: 30,
            opacity: 0,
            duration: 0.5,
            delay: i * 0.08,
            ease: 'power3.out'
        });
    });

    // Tech cards
    gsap.utils.toArray('.tech-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 92%',
                toggleActions: 'play none none reverse'
            },
            y: 20,
            opacity: 0,
            duration: 0.4,
            delay: i * 0.05,
            ease: 'power3.out'
        });
    });

    // CTA section
    gsap.from('.cta-content', {
        scrollTrigger: {
            trigger: '.section-cta',
            start: 'top 75%',
            toggleActions: 'play none none reverse'
        },
        y: 50,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out'
    });

    // Problem illustration
    gsap.from('.problem-illustration', {
        scrollTrigger: {
            trigger: '.problem-illustration',
            start: 'top 80%',
            toggleActions: 'play none none reverse'
        },
        scale: 0.9,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out'
    });
}

/* =====================================================
   Fallback Animations (if GSAP not loaded)
   ===================================================== */
function initFallbackAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Add animation classes
    document.querySelectorAll('.section-header, .problem-stat, .flow-step, .feature-card, .tech-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Define the animate-in class
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);
}

/* =====================================================
   Counter Animations
   ===================================================== */
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');

    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => observer.observe(counter));
}

function animateCounter(element) {
    const target = parseFloat(element.getAttribute('data-count'));
    const prefix = element.getAttribute('data-prefix') || '';
    const suffix = element.getAttribute('data-suffix') || '';
    const duration = 2000;
    const steps = 60;
    const stepDuration = duration / steps;

    let current = 0;
    const increment = target / steps;
    const isFloat = target % 1 !== 0;

    const timer = setInterval(() => {
        current += increment;

        if (current >= target) {
            current = target;
            clearInterval(timer);
        }

        const displayValue = isFloat ? current.toFixed(1) : Math.floor(current);
        element.textContent = prefix + displayValue + suffix;
    }, stepDuration);
}

/* =====================================================
   Parallax Effects
   ===================================================== */
function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax-layer');
    const floatingElements = document.querySelectorAll('.floating-element');
    const abstractShapes = document.querySelectorAll('.abstract-shape');

    if (parallaxElements.length === 0 && floatingElements.length === 0) return;

    // Check for reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    let ticking = false;

    function updateParallax() {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;

        // Update floating elements based on scroll
        floatingElements.forEach((el, index) => {
            const speed = 0.05 + (index * 0.02);
            const yOffset = scrollY * speed;
            el.style.transform = `translateY(${yOffset}px)`;
        });

        // Update abstract shapes
        abstractShapes.forEach((shape, index) => {
            const speed = 0.03 + (index * 0.015);
            const yOffset = scrollY * speed;
            shape.style.transform = `translateY(${-yOffset}px)`;
        });

        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }, { passive: true });

    // Mouse parallax for hero section
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const { width, height } = hero.getBoundingClientRect();

            const xPercent = (clientX / width - 0.5) * 2;
            const yPercent = (clientY / height - 0.5) * 2;

            floatingElements.forEach((el, index) => {
                const intensity = 10 + (index * 5);
                const x = xPercent * intensity;
                const y = yPercent * intensity;
                el.style.transform = `translate(${x}px, ${y}px)`;
            });
        });

        hero.addEventListener('mouseleave', () => {
            floatingElements.forEach(el => {
                el.style.transform = 'translate(0, 0)';
                el.style.transition = 'transform 0.5s ease-out';
            });
        });

        hero.addEventListener('mouseenter', () => {
            floatingElements.forEach(el => {
                el.style.transition = 'transform 0.1s ease-out';
            });
        });
    }
}

/* =====================================================
   Phone Mockup Interaction
   ===================================================== */
document.addEventListener('DOMContentLoaded', () => {
    const phoneMockup = document.querySelector('.phone-frame');

    if (phoneMockup) {
        let isHovering = false;
        let targetRotateX = 3;
        let targetRotateY = -3;
        let currentRotateX = 3;
        let currentRotateY = -3;

        // Smooth animation loop
        function animatePhone() {
            // Ease towards target
            currentRotateX += (targetRotateX - currentRotateX) * 0.08;
            currentRotateY += (targetRotateY - currentRotateY) * 0.08;

            phoneMockup.style.transform = `perspective(1000px) rotateX(${currentRotateX}deg) rotateY(${currentRotateY}deg)`;

            requestAnimationFrame(animatePhone);
        }

        animatePhone();

        // Subtle 3D effect on hover with smooth interpolation
        phoneMockup.addEventListener('mousemove', (e) => {
            isHovering = true;
            const rect = phoneMockup.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Reduced intensity for subtlety
            targetRotateX = ((y - centerY) / centerY) * 5;
            targetRotateY = ((centerX - x) / centerX) * 5;
        });

        phoneMockup.addEventListener('mouseleave', () => {
            isHovering = false;
            // Return to default position
            targetRotateX = 3;
            targetRotateY = -3;
            document.addEventListener('DOMContentLoaded', () => {
                const routeSteps = document.querySelectorAll('.route-step');

                if (routeSteps.length > 0) {
                    // Animate steps sequentially
                    routeSteps.forEach((step, index) => {
                        step.style.opacity = '0';
                        step.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                            step.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                            step.style.opacity = '1';
                            step.style.transform = 'translateX(0)';
                        }, 3500 + (index * 300));
                    });
                }
            });

            /* =====================================================
               Smooth Reveal on Scroll
               ===================================================== */
            function initSmoothReveal() {
                const revealElements = document.querySelectorAll('[data-reveal]');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -100px 0px'
                });

                revealElements.forEach(el => observer.observe(el));
            }

            // Initialize smooth reveal
            document.addEventListener('DOMContentLoaded', initSmoothReveal);
        });
    }
});
