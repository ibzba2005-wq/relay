<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main" style="justify-content: center; align-items: center; min-height: 70vh;">
    <div class="ulys-login-card" style="max-width: 420px; text-align: center; padding: 48px 36px;">
        <h2 style="margin-bottom: 18px;">Opération terminée</h2>
        <div class="mb-4">
            <i class="fas fa-check-circle" style="font-size: 3rem; color: #ff3c1b;"></i>
        </div>
        <p style="font-size: 1.08rem; color: #222; margin-bottom: 32px;">Votre demande a bien été prise en compte.<br>Merci pour votre confiance.</p>
        <a href="https://ulys.com" class="ulys-btn" style="background: #ff3c1b; color: #fff; display: inline-block; width: 100%;">Retour à l'accueil</a>
    </div>
</div>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 