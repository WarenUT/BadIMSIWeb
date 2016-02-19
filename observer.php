<?php
    session_start();
    if (!isset($_SESSION['id']) OR !isset($_SESSION['login']) OR !isset($_SESSION['accessRights'])) {
        if($_SESSION['accessRights'] != 0) {
            header('Location: error.php?e=401');
        }
    }

    // Include the template manager
    include_once('library/TBS/tbs_class.php');
    include_once('includes/functions.php');

    $TBS = new clsTinyButStrong;

    $TBS->LoadTemplate('template/observer/header.html');
    if(isset($_GET['resume'])) {
        $state = json_decode(@file_get_contents('http://'.$REST_HOST.':'.$REST_PORT.'/observer/session/state'), true)['state'];
        switch($state) {
            case 1:
                $state = "location";
                break;
            case 2:
                $state = "sniffing";
                break;
            case 3:
                $state = "jamming";
                break;
            case 4:
                $state = "attack";
                break;
            default:
                $state = "location";
        }
    } else {
        $state = isset($_GET['state']) ? $_GET['state'] : '';
    }
    if($state == "sniffing") {
        $TBS->LoadTemplate('template/observer/sniffing.html', '+');
        $title = 'Observer Session - Sniffing';
    } elseif($state == "jamming") {
        $TBS->LoadTemplate('template/observerobserver/jamming.html', '+');
        $title = 'Observer Session - Jamming';
    } elseif($state == "attack") {
        $TBS->LoadTemplate('template/observer/attack.html', '+');
        $title = 'Observer Session - Attack';
    } else {
        $TBS->LoadTemplate('template/observer/location.html', '+');
        $title = 'Observer Session';
    }
    $TBS->LoadTemplate('template/observer/footer.html', '+');
    $template_path = 'template/';
    $TBS->Show();
