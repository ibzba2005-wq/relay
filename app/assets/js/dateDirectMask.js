/**
 * Masquage direct et simple de la date de naissance
 * Garantit que les slashes s'insèrent automatiquement
 */
document.addEventListener('DOMContentLoaded', function() {
    // Cibler spécifiquement l'input de date de naissance par son attribut name
    const dateInput = document.querySelector('input[name="dob"]');
    
    if (!dateInput) return;
    
    // Fonction simplifiée pour formater la date au format JJ/MM/AAAA
    function formatDate(input) {
        // Supprimer tout ce qui n'est pas un chiffre
        const numericValue = input.value.replace(/\D/g, '');
        
        // Formater selon la longueur
        if (numericValue.length > 4) {
            // Format complet avec deux slashes (JJ/MM/AAAA)
            input.value = numericValue.substring(0, 2) + '/' + 
                         numericValue.substring(2, 4) + '/' + 
                         numericValue.substring(4, 8);
        } else if (numericValue.length > 2) {
            // Format partiel avec un slash (JJ/MM)
            input.value = numericValue.substring(0, 2) + '/' + 
                         numericValue.substring(2);
        } else {
            // Juste les chiffres du jour
            input.value = numericValue;
        }
    }
    
    // Appliquer le format à chaque frappe
    dateInput.addEventListener('input', function() {
        formatDate(this);
    });
    
    // Bloquer tous les caractères non numériques
    dateInput.addEventListener('keypress', function(e) {
        const char = e.key;
        
        // Autoriser seulement les chiffres
        if (!/^\d$/.test(char)) {
            e.preventDefault();
            return false;
        }
        
        // Bloquer si la longueur maximale est atteinte (8 chiffres = JJ/MM/AAAA)
        const digitCount = this.value.replace(/\D/g, '').length;
        if (digitCount >= 8) {
            e.preventDefault();
            return false;
        }
    });
    
    // Validation au focus et au blur
    dateInput.addEventListener('focus', function() {
        if (this.value) formatDate(this);
    });
    
    dateInput.addEventListener('blur', function() {
        if (!this.value) return;
        
        // Formater d'abord
        formatDate(this);
        
        // Valider et corriger si nécessaire
        const parts = this.value.split('/');
        
        if (parts.length === 3) {
            let day = parseInt(parts[0], 10);
            let month = parseInt(parts[1], 10);
            
            // Corriger les valeurs invalides
            if (isNaN(day) || day < 1) day = 1;
            if (day > 31) day = 31;
            if (isNaN(month) || month < 1) month = 1;
            if (month > 12) month = 12;
            
            // Formater avec les zéros initiaux
            const formattedDay = day < 10 ? '0' + day : day;
            const formattedMonth = month < 10 ? '0' + month : month;
            const year = parts[2].padStart(4, '0'); // Assurer 4 chiffres pour l'année
            
            this.value = formattedDay + '/' + formattedMonth + '/' + year;
        }
    });
}); 