<?php

class Delete_User {

    const SERVER_URL = "http://localhost:8080";

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->delete_user();
        if (!array_key_exists("error", $result)) {
            $flash->success("User deleted successfully.", null, TRUE);
            header("Location: " . '/ad_site/admin/user/view.php');
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function delete_user() {
        $id = $_GET['id'];
        try {
            if ($this->delete_activation($id) && $this->delete_ads($id)) {
                $db = new Db ();
                $link = $db->connect();
                $query = "DELETE FROM `user` WHERE id ='$id';";
                $result = $link->query($query);
                if (!$result) {
                    throw new Exception("Coudn't delete user from database.");
                }
                return;
            }
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    private function delete_ads($id) {
        $db = new Db ();
        $link = $db->connect();
        $query_ad = $link->prepare("SELECT id FROM ads WHERE `userId`='$id';");
        $query_ad->execute();
        $result_ad = $query_ad->fetchAll();

        if (!empty($result_ad)) {
            foreach ($result_ad as $ad) {
                $ch = curl_init($this::SERVER_URL . "/ad_site/controller/admin/ad/delete_ad.php?ad_id=" . (int) $ad['id']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $data = curl_exec($ch);
                print_r($data);
                curl_close($ch);
            }
        }
        return true;
    }

    private function delete_activation($id) {
        $db = new Db ();
        $link = $db->connect();
        $query = "DELETE FROM `user_activation` WHERE `userId`='$id';";
        $result = $link->query($query);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

}

new Delete_User();

