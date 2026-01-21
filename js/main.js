document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.classList.toggle('open');
        });
    }

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
                // Close mobile menu if open
                navLinks.classList.remove('active');
                if (mobileMenuBtn) mobileMenuBtn.classList.remove('open');
            }
        });
    });

    // Sticky Header Effect
    const header = document.querySelector('.main-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Simple Intersection Observer for Fade-in animations
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });

    // Phone Input Validation (Only Numbers)
    const phoneInput = document.getElementById('telefono');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Contact Form Handling
    const contactForm = document.getElementById('contactForm');
    const captchaLabel = document.getElementById('captchaLabel');

    // Function to load dynamic captcha
    function loadCaptcha() {
        if (captchaLabel) {
            console.log('Fetching captcha...');
            fetch('get_captcha.php?t=' + new Date().getTime())
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Captcha loaded:', data);
                    captchaLabel.innerText = `Pregunta de seguridad: ${data.question} *`;
                })
                .catch(err => {
                    console.error('Error loading captcha:', err);
                    captchaLabel.innerText = 'Pregunta de seguridad: Error al cargar (Recarga la página) *';
                });
        }
    }

    // Load captcha on page load
    loadCaptcha();

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            const statusDiv = document.getElementById('formStatus');

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerText = 'Enviando...';
            statusDiv.style.display = 'none';

            const formData = new FormData(this);

            fetch('send_mail.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    statusDiv.style.display = 'block';
                    statusDiv.innerText = data.message;

                    if (data.success) {
                        statusDiv.style.backgroundColor = '#d4edda';
                        statusDiv.style.color = '#155724';
                        statusDiv.style.border = '1px solid #c3e6cb';
                        contactForm.reset();
                        loadCaptcha(); // Refresh captcha
                    } else {
                        statusDiv.style.backgroundColor = '#f8d7da';
                        statusDiv.style.color = '#721c24';
                        statusDiv.style.border = '1px solid #f5c6cb';
                        loadCaptcha(); // Refresh captcha
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusDiv.style.display = 'block';
                    statusDiv.innerText = 'Ocurrió un error al intentar enviar el mensaje. Por favor intenta nuevamente.';
                    statusDiv.style.backgroundColor = '#f8d7da';
                    statusDiv.style.color = '#721c24';
                    statusDiv.style.border = '1px solid #f5c6cb';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalBtnText;
                });
        });
    }
});
