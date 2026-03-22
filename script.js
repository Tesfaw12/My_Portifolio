// Mobile Navigation Toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    hamburger.classList.toggle('active');
});

document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Navbar background on scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    navbar.style.backgroundColor = window.scrollY > 50
        ? 'rgba(15, 23, 42, 0.98)'
        : 'rgba(15, 23, 42, 0.95)';
});

// Contact form — fully static, opens email client
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');

contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();

    const body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\n\n${message}`);
    window.location.href = `mailto:tesfawamare19125@gmail.com?subject=${encodeURIComponent(subject)}&body=${body}`;

    formMessage.innerHTML = '<div class="alert alert-success">Your email client should open now. If not, email tesfawamare19125@gmail.com directly.</div>';
    contactForm.reset();
});

// Scroll animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('section').forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    observer.observe(section);
});

document.querySelectorAll('.skill-card, .project-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = `opacity 0.5s ease-out ${index * 0.1}s, transform 0.5s ease-out ${index * 0.1}s`;
    observer.observe(card);
});

// Active nav link on scroll
window.addEventListener('scroll', () => {
    let current = '';
    document.querySelectorAll('section').forEach(section => {
        if (window.scrollY >= section.offsetTop - 100) {
            current = section.getAttribute('id');
        }
    });
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.toggle('active', link.getAttribute('href').slice(1) === current);
    });
});

// Typing effect
const tagline = document.querySelector('.tagline');
const originalText = tagline.textContent;
tagline.textContent = '';
let i = 0;
function typeWriter() {
    if (i < originalText.length) {
        tagline.textContent += originalText.charAt(i++);
        setTimeout(typeWriter, 100);
    }
}
window.addEventListener('load', () => setTimeout(typeWriter, 500));

// Profile image fallback
const profileImg = document.getElementById('profileImg');
if (profileImg) {
    profileImg.addEventListener('error', function () {
        this.style.display = 'none';
        const placeholder = document.createElement('div');
        placeholder.className = 'profile-placeholder';
        placeholder.innerHTML = '<i class="fas fa-user"></i>';
        this.parentElement.appendChild(placeholder);
    });
}

// Mouse glow effect
const glow = document.createElement('div');
glow.style.cssText = `
    position:fixed; width:400px; height:400px; border-radius:50%;
    background:radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
    pointer-events:none; z-index:0; transform:translate(-50%,-50%);
    transition: left 0.15s ease, top 0.15s ease;
`;
document.body.appendChild(glow);
document.addEventListener('mousemove', e => {
    glow.style.left = e.clientX + 'px';
    glow.style.top  = e.clientY + 'px';
});

// Card 3D tilt
document.querySelectorAll('.skill-card, .cert-card, .project-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const rotX = ((e.clientY - rect.top  - rect.height / 2) / (rect.height / 2)) * -8;
        const rotY = ((e.clientX - rect.left - rect.width  / 2) / (rect.width  / 2)) *  8;
        card.style.transform = `perspective(600px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-8px)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
});

// Glowing border on contact items
document.querySelectorAll('.contact-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        item.style.boxShadow = '-4px 0 20px rgba(99,102,241,0.3), 0 8px 30px rgba(0,0,0,0.3)';
    });
    item.addEventListener('mouseleave', () => { item.style.boxShadow = ''; });
});
