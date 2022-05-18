<?php

class Logout {

    public function __construct() {
        require_once '../templates/startSession.php';
        include '../scripts/flash.php';
        unset($_SESSION['user']);

        $flash = new FlashMessages();
        $flash->success("You are logged out successfully.");
        
        header("Location: ../home.php");
    }

}

new Logout();
