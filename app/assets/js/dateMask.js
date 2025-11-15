/**
 * Script de masque pour date de naissance
 * Format DD/MM/YYYY avec insertion automatique des séparateurs
 */
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les champs avec la classe date-mask
    const dateFields = document.querySelectorAll('.date-mask');
    
    if (!dateFields.length) return;
    
    dateFields.forEach(function(field) {
        // Technique plus directe : détecter chaque caractère tapé et formater instantanément
        field.addEventListener('input', function(e) {
            // Si l'utilisateur supprime des caractères, laisser faire
            if (e.inputType === 'deleteContentBackward' || e.inputType === 'deleteContentForward') {
                return;
            }
            
            // Obtenir seulement les chiffres
            let valeurSansSlash = this.value.replace(/[^0-9]/g, '');
            
            // Limiter à 8 chiffres maximum (format DDMMYYYY)
            valeurSansSlash = valeurSansSlash.substring(0, 8);
            
            // Formater avec des slashes
            let valeurFormatee = '';
            
            // Formater en JJ/MM/AAAA
            for (let i = 0; i < valeurSansSlash.length; i++) {
                // Ajouter un slash après la position 2 (jour)
                if (i === 2) {
                    valeurFormatee += '/';
                }
                // Ajouter un slash après la position 4 (mois)
                if (i === 4) {
                    valeurFormatee += '/';
                }
                valeurFormatee += valeurSansSlash[i];
            }
            
            // Mettre à jour la valeur du champ
            this.value = valeurFormatee;
        });
        
        // Bloquer directement tous les caractères non numériques
        field.addEventListener('keypress', function(e) {
            // Permettre seulement les chiffres
            if (!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });
        
        // Validation additionnelle sur le collage
        field.addEventListener('paste', function(e) {
            e.preventDefault();
            const texteColle = (e.clipboardData || window.clipboardData).getData('text');
            
            // Récupérer seulement les chiffres du texte collé
            const chiffresUniquement = texteColle.replace(/[^0-9]/g, '').substring(0, 8);
            
            // Reconstruire le format avec slashes
            let resultat = '';
            for (let i = 0; i < chiffresUniquement.length; i++) {
                if (i === 2) resultat += '/';
                if (i === 4) resultat += '/';
                resultat += chiffresUniquement[i];
            }
            
            // Insérer directement le résultat formaté
            this.value = resultat;
        });
        
        // Validation finale lorsque l'utilisateur quitte le champ
        field.addEventListener('blur', function() {
            if (!this.value) return;
            
            // Si la valeur contient des slashes, la diviser
            const parties = this.value.split('/');
            
            if (parties.length === 3) {
                // Corriger les valeurs du jour et du mois si nécessaire
                let jour = parseInt(parties[0], 10);
                let mois = parseInt(parties[1], 10);
                
                if (isNaN(jour) || jour < 1) jour = 1;
                if (jour > 31) jour = 31;
                if (isNaN(mois) || mois < 1) mois = 1;
                if (mois > 12) mois = 12;
                
                // Formater avec les zéros de début si nécessaire
                const jourFormate = jour < 10 ? '0' + jour : jour;
                const moisFormate = mois < 10 ? '0' + mois : mois;
                let anneeFormatee = parties[2];
                
                // S'assurer que l'année a 4 chiffres
                while (anneeFormatee.length < 4) {
                    anneeFormatee = '0' + anneeFormatee;
                }
                
                // Appliquer le format corrigé
                this.value = jourFormate + '/' + moisFormate + '/' + anneeFormatee;
            }
        });
    });
}); 