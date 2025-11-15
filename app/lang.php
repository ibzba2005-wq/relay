<?php
$detect = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

switch ($detect) {

    case 'fr':
        include('lang/es.php');
        break;
    default:
        include('lang/es.php');
        break;
}

?>