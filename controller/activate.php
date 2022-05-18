<?php

class Activate {

    public function __construct() {
        require_once '../templates/startSession.php';
        include_once ("../model/db/db.php");
        include '../scripts/flash.php';

        echo "Please Wait ...";
        $flash = new FlashMessages();

        if (isset($_GET['code'])) {
            $result = $this->findUserByActivation($_GET['code']);
            if (!array_key_exists("error", $result)) {
                $this->activate($result['id']);
                $flash->success("User Activated successfully. Please Login", null, TRUE);
            } else {
                $flash->error($result['error'], null, TRUE);
            }
        } else {
            $flash->error("Invalid Verfication Code", null, TRUE);
        }
        if (isset($_GET ['return']))
            $url = $_GET ['return'];
        else
            $url = "/ad_site/home.php";
        header("Location: $url");
    }

    private function activate($userId) {
        try {
            $db = new Db ();
            $link = $db->connect();
            $result = $link->query("UPDATE `user` SET status = '1' WHERE id = '$userId'");
            if (!$result) {
                throw new Exception("Coudn't activate user.");
            }
            $result = $link->query("DELETE FROM user_activation WHERE `userId` = '$userId'");
            if (!$result) {
                throw new Exception("Coudn't activate user.");
            }

            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    private function findUserByActivation($code) {
        try {
            $db = new Db ();
            $link = $db->connect();
            $query = $link->prepare("SELECT `userId` FROM user_activation WHERE `verificationCode` = '$code'");
            $query->execute();
            $result = $query->fetch();
            if (!$result) {
                throw new Exception("Coudn't find user to with the provided code.");
            }
            return ['id' => $result['userId']];
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Activate();

