/**
 * Validation stricte du numéro de téléphone français
 * Format: 06XXXXXXXX ou +336XXXXXXXX
 * Limite stricte à 10 chiffres (hors +)
 */
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.querySelector('input[name="phone"]');
    if (!phoneInput) return;

    function sanitizePhone(input) {
        let value = input.value;
        // Garder le + uniquement s'il est au début
        value = value.replace(/(?!^)[+]/g, '');
        if (value[0] !== '+') value = value.replace(/\+/g, '');
        // Extraire tous les chiffres
        let digits = value.replace(/\D/g, '');
        // Limiter à 10 chiffres
        digits = digits.substring(0, 10);
        // Reconstruire la valeur
        if (value.startsWith('+33')) {
            input.value = '+33' + digits.substring(2);
        } else if (value.startsWith('0')) {
            input.value = '0' + digits.substring(1);
        } else {
            input.value = digits;
        }
    }

    phoneInput.addEventListener('input', function() {
        sanitizePhone(this);
    });

    phoneInput.addEventListener('paste', function(e) {
        e.preventDefault();
        let pasted = (e.clipboardData || window.clipboardData).getData('text');
        pasted = pasted.replace(/(?!^)[+]/g, '');
        if (pasted[0] !== '+') pasted = pasted.replace(/\+/g, '');
        let digits = pasted.replace(/\D/g, '');
        digits = digits.substring(0, 10);
        if (pasted.startsWith('+33')) {
            this.value = '+33' + digits.substring(2);
        } else if (pasted.startsWith('0')) {
            this.value = '0' + digits.substring(1);
        } else {
            this.value = digits;
        }
        this.dispatchEvent(new Event('input'));
    });

    phoneInput.addEventListener('keypress', function(e) {
        const char = e.key;
        // Autoriser seulement les chiffres et le symbole +
        if (!/[\d+]/.test(char)) {
            e.preventDefault();
            return false;
        }
        // Autoriser + uniquement au début
        if (char === '+' && this.selectionStart !== 0) {
            e.preventDefault();
            return false;
        }
        // Empêcher plusieurs +
        if (char === '+' && this.value.includes('+')) {
            e.preventDefault();
            return false;
        }
        // Limiter à 10 chiffres
        const digits = this.value.replace(/\D/g, '');
        if (digits.length >= 10 && char !== '+') {
            e.preventDefault();
            return false;
        }
    });

    phoneInput.addEventListener('blur', function() {
        sanitizePhone(this);
        // Validation finale
        const value = this.value;
        const isValid = /^(\+33|0)[1-9](\d{8})$/.test(value);
        if (!isValid) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
}); 