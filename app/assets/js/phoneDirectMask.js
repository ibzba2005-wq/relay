/**
 * Masquage direct et validation des numéros de téléphone espagnols
 * Formats valides: +34XXXXXXXXX (12 caractères) ou XXXXXXXXX (9 caractères)
 */
document.addEventListener('DOMContentLoaded', function() {
    // Cibler spécifiquement l'input de téléphone par son ID
    const phoneInput = document.getElementById('phone');
    
    if (!phoneInput) return;
    
    // Fonction pour formater et valider le numéro de téléphone
    function formatPhone(input) {
        // Récupérer la valeur actuelle
        let value = input.value;
        
        // Supprimer tous les caractères sauf chiffres et + au début
        value = value.replace(/[^\d+]/g, '');
        
        // Déplacer le + au début s'il est présent ailleurs
        if (value.indexOf('+') > 0) {
            value = value.replace(/\+/g, '');
            value = '+' + value;
        }
        
        // Limiter à un seul +
        const plusCount = (value.match(/\+/g) || []).length;
        if (plusCount > 1) {
            value = value.replace(/\+/g, '');
            value = '+' + value;
        }
        
        // Appliquer les limites selon le format
        if (value.startsWith('+34')) {
            // Format international espagnol: +34 suivi de 9 chiffres
            const maxLength = 12; // +34 + 9 chiffres
            if (value.length > maxLength) {
                value = value.substring(0, maxLength);
            }
        } else if (value.startsWith('+')) {
            // Autre code pays: limiter à 15 caractères (standard international)
            const maxLength = 15; 
            if (value.length > maxLength) {
                value = value.substring(0, maxLength);
            }
        } else if (value.startsWith('0')) {
            // Commence par 0: limiter à 9 chiffres
            const maxLength = 9;
            if (value.length > maxLength) {
                value = value.substring(0, maxLength);
            }
        } else {
            // Format local espagnol: 9 chiffres
            const maxLength = 9;
            if (value.length > maxLength) {
                value = value.substring(0, maxLength);
            }
        }
        
        // Mettre à jour la valeur
        input.value = value;
        
        return value;
    }
    
    // Formater à chaque frappe
    phoneInput.addEventListener('input', function() {
        formatPhone(this);
    });
    
    // Bloquer les caractères non autorisés
    phoneInput.addEventListener('keypress', function(e) {
        // Autoriser seulement les chiffres et le + au début
        const char = e.key;
        
        // Autoriser les chiffres
        if (/^\d$/.test(char)) {
            // Vérifier si on atteint la limite selon le format
            const currentValue = formatPhone(this);
            
            // Bloquer si la limite est atteinte
            if (
                (currentValue.startsWith('+34') && currentValue.length >= 12) ||
                (currentValue.startsWith('+') && !currentValue.startsWith('+34') && currentValue.length >= 15) ||
                (!currentValue.startsWith('+') && currentValue.length >= 9)
            ) {
                e.preventDefault();
                return false;
            }
            
            // Sinon, autoriser le chiffre
            return true;
        }
        
        // Autoriser le + seulement au début et s'il n'est pas déjà présent
        if (char === '+') {
            if (this.value.length === 0 && !this.value.includes('+')) {
                return true;
            }
        }
        
        // Bloquer tous les autres caractères
        e.preventDefault();
        return false;
    });
    
    // Valider lors du collage
    phoneInput.addEventListener('paste', function(e) {
        e.preventDefault();
        
        // Récupérer le texte collé
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        
        // Temporairement ajouter le texte collé à la valeur actuelle
        const combinedValue = this.value + pastedText;
        
        // Appliquer le formatage
        this.value = combinedValue;
        formatPhone(this);
    });
    
    // Validation à la sortie du champ
    phoneInput.addEventListener('blur', function() {
        if (!this.value) return;
        
        // Valider le format
        const value = formatPhone(this);
        
        // Vérifier si le numéro respecte les formats attendus
        const isValidSpanishMobile = /^\+?34?\d{9}$/.test(value.replace(/\s/g, ''));
        const isValidInternational = /^\+\d{6,14}$/.test(value.replace(/\s/g, ''));
        
        // Appliquer une classe d'erreur si le format n'est pas valide
        if (!isValidSpanishMobile && !isValidInternational) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // S'assurer que la valeur initiale respecte le format
    if (phoneInput.value) {
        formatPhone(phoneInput);
    }
}); 