<?php

class Login {

    public function __construct() {
        require_once '../templates/startSession.php';
        include_once ("../model/db/db.php");
        include '../scripts/flash.php';

        echo "Please Wait ...";
        $output = $this->login();
        $flash = new FlashMessages();

        if (!array_key_exists('error', $output)) {
            $_SESSION['user'] = $output;
            $flash->success("Logged in successfully as " . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . ".");
            $url = $_GET ['return'];
            header("Location: $url");
            return;
        } else {
            $flash->error($output['error'], null, TRUE);
            header("Location: " . "/ad_site/login.php?return=" . $_GET ['return']);
            return;
        }
    }

    private function login() {
        if (!(isset($_POST ['email']) and $_POST ['email'] !== "") && !(isset($_POST ['password']) and $_POST ['password'] !== "")) {
            return ['error' => "Please enter the email and password for login"];
        }

        $postData = array(
            'email' => $_POST ['email'],
            'pass' => $_POST ['password'],
        );
        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "SELECT * FROM `user` WHERE email='" . $postData['email'] . "' AND password='" . $postData['pass'] . "' LIMIT 1";
            $result = $link->query($query);
            $user = $result->fetch(PDO::FETCH_ASSOC);
            if (empty($user)) {
                throw new Exception("No User Found in our database with the provided credentials. Please try again.");
            } else if ($user['status'] == 0) {
                throw new Exception("User is not activated");
            } else {
                return $user;
            }
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Login();
