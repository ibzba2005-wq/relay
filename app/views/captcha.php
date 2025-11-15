<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main" style="justify-content: center; align-items: center; min-height: 70vh;">
    <div class="ulys-login-card" style="max-width: 420px; text-align: center; padding: 48px 36px;">
        <h2 style="margin-bottom: 18px;">Vérification de sécurité</h2>
        <div class="mb-4">
            <span class="ulys-orange">Merci de valider le captcha pour continuer.</span>
        </div>
        <form method="POST" action="index.php?view=captcha">
            <input type="hidden" name="catch">
            <div class="ulys-form-group" style="margin-bottom: 32px;">
                <div id="captcha-widget" class="d-flex justify-content-center mb-3">
                    <div class="h-captcha" data-sitekey="<?= SITEKEY ?>"></div>
                </div>
                <?php if (isset($_SESSION['ERRORS']['captcha'])): ?>
                    <div class="invalid-feedback d-block" style="margin-top: 8px; color: #ff3c1b;">
                        
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" value="captcha" class="ulys-btn" style="background: #ff3c1b; color: #fff;">Valider</button>
        </form>
    </div>
</div>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 