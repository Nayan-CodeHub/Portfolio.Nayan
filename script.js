// Initialize Particle.js for the Hero Background
particlesJS('particles-js', {
    "particles": {
      "number": {
        "value": 60,
        "density": {
          "enable": true,
          "value_area": 800
        }
      },
      "color": {
        "value": "#34d399" // Matches the primary neon green
      },
      "shape": {
        "type": "circle",
        "stroke": {
          "width": 0,
          "color": "#000000"
        }
      },
      "opacity": {
        "value": 0.5,
        "random": false
      },
      "size": {
        "value": 3,
        "random": true
      },
      "line_linked": {
        "enable": true,
        "distance": 150,
        "color": "#34d399",
        "opacity": 0.3,
        "width": 1
      },
      "move": {
        "enable": true,
        "speed": 1.5,
        "direction": "none",
        "random": false,
        "straight": false,
        "out_mode": "out",
        "bounce": false
      }
    },
    "interactivity": {
      "detect_on": "canvas",
      "events": {
        "onhover": {
          "enable": true,
          "mode": "grab"
        },
        "onclick": {
          "enable": true,
          "mode": "push"
        },
        "resize": true
      },
      "modes": {
        "grab": {
          "distance": 180,
          "line_linked": {
            "opacity": 0.8
          }
        },
        "push": {
          "particles_nb": 3
        }
      }
    },
    "retina_detect": true
});
  
// Smooth Scroll for Buttons
document.querySelector('.scroll-down')?.addEventListener('click', () => {
    document.querySelector('#about')?.scrollIntoView({ behavior: 'smooth' });
});

// Theme toggle
const themeToggle = document.getElementById('themeToggle');
const root = document.documentElement;
const savedTheme = localStorage.getItem('portfolio-theme');

if (savedTheme) {
    root.setAttribute('data-theme', savedTheme);
}

if (themeToggle) {
    const icon = themeToggle.querySelector('i');
    const updateToggleIcon = () => {
        const isLight = root.getAttribute('data-theme') === 'light';
        icon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
    };

    updateToggleIcon();
    themeToggle.addEventListener('click', () => {
        const nextTheme = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        root.setAttribute('data-theme', nextTheme);
        localStorage.setItem('portfolio-theme', nextTheme);
        updateToggleIcon();
    });
}

// Initialize AOS (already done, but verify here)
AOS.init({
  once: true,
  offset: 100
});

// Smooth Scrolling for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
    });
});

// Update footer year
document.getElementById('year').textContent = new Date().getFullYear();

// Add a subtle nav scroll effect
const nav = document.querySelector('nav');
window.addEventListener('scroll', () => {
    nav?.classList.toggle('scrolled', window.scrollY > 30);
});

// Typing effect for hero headline
const heroText = document.getElementById('hero-typed');
const phrases = ['Building smart applications', 'Turning ideas into impact', 'Exploring AI with curiosity'];
let phraseIndex = 0;
let charIndex = 0;
let deleting = false;

function typeLoop() {
    if (!heroText) return;

    if (!deleting && charIndex < phrases[phraseIndex].length) {
        heroText.textContent = phrases[phraseIndex].slice(0, charIndex + 1);
        charIndex++;
        setTimeout(typeLoop, 80);
    } else if (!deleting && charIndex === phrases[phraseIndex].length) {
        deleting = true;
        setTimeout(typeLoop, 1200);
    } else if (deleting && charIndex > 0) {
        heroText.textContent = phrases[phraseIndex].slice(0, charIndex - 1);
        charIndex--;
        setTimeout(typeLoop, 45);
    } else {
        deleting = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        setTimeout(typeLoop, 300);
    }
}

typeLoop();

// Contact form handling
const contactForm = document.getElementById('contactForm');
const formStatus = document.getElementById('formStatus');

if (contactForm && formStatus) {
    contactForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(contactForm);
        formStatus.textContent = 'Sending your message...';

        try {
            const response = await fetch('contact.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                formStatus.textContent = result.message;
                contactForm.reset();
            } else {
                formStatus.textContent = result.message || 'Something went wrong.';
            }
        } catch (error) {
            formStatus.textContent = 'Unable to send message right now.';
        }
    });
}
