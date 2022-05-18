<?php

class enable_user {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->disable_user();
        if (!array_key_exists("error", $result)) {
            $flash->success("User enabled successfully.", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function disable_user() {
        $id = $_GET['id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE `user` SET status = '1' WHERE `user`.id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't enable user in database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new enable_user();