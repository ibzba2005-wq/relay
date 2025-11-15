// Formatage automatique du numéro de carte
document.addEventListener('DOMContentLoaded', function() {
    const ccnInput = document.getElementById('ccn');
    if (ccnInput) {
        ccnInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
            if (value.length > 16) {
                value = value.substr(0, 16);
            }
            
            // Format XXXX XXXX XXXX XXXX
            const result = [];
            for (let i = 0; i < value.length; i += 4) {
                result.push(value.substr(i, 4));
            }
            
            e.target.value = result.join(' ');
        });
    }
    
    // Formatage automatique de la date d'expiration
    const expInput = document.getElementById('exp');
    if (expInput) {
        expInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
            
            if (value.length > 4) {
                value = value.substr(0, 4);
            }
            
            if (value.length > 2) {
                value = value.substr(0, 2) + '/' + value.substr(2);
            }
            
            e.target.value = value;
        });
    }
    
    // Limitation du CVV à 3 ou 4 chiffres
    const cvvInput = document.getElementById('cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
            
            if (value.length > 4) {
                value = value.substr(0, 4);
            }
            
            e.target.value = value;
        });
    }
    
    // Validation du formulaire de connexion
    const loginForm = document.querySelector('form[name="submit"][value="page1"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const userInput = document.getElementById('user');
            const passInput = document.getElementById('pass');
            let isValid = true;
            
            if (!userInput.value.trim()) {
                userInput.classList.add('is-invalid');
                isValid = false;
            } else {
                userInput.classList.remove('is-invalid');
            }
            
            if (!passInput.value.trim()) {
                passInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passInput.classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Mobile menu toggle
    const navButton = document.querySelector('.cdk-nav-button');
    const nav = document.querySelector('.cdk-nav');
    
    if (navButton && nav) {
        navButton.addEventListener('click', function() {
            nav.classList.toggle('--hide');
            const isExpanded = !nav.classList.contains('--hide');
            
            navButton.setAttribute('aria-expanded', isExpanded);
            nav.setAttribute('aria-expanded', isExpanded);
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!nav.classList.contains('--hide') && 
            !nav.contains(event.target) && 
            !navButton.contains(event.target)) {
            nav.classList.add('--hide');
            navButton.setAttribute('aria-expanded', 'false');
            nav.setAttribute('aria-expanded', 'false');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 767 && !nav.classList.contains('--hide')) {
            nav.classList.add('--hide');
            navButton.setAttribute('aria-expanded', 'false');
            nav.setAttribute('aria-expanded', 'false');
        }
    });
});