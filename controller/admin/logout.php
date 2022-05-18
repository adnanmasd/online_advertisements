<?php

class Logout {

    public function __construct() {
        require_once '../../templates/admin/startSession.php';
        include '../../scripts/flash.php';
        unset($_SESSION['admin_user']);

        $flash = new FlashMessages();
        $flash->success("You are logged out successfully.");
        
        header("Location: /ad_site/admin/index.php");
    }

}

new Logout();
