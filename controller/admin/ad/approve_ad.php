<?php

class Approve_ad {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->approve_ad();
        if (!array_key_exists("error", $result)) {
            $flash->success("Ad approved successfully.", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function approve_ad() {
        $id = $_GET['ad_id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE ads a SET a.status = '1', a.date_approved = now() WHERE id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't approve ad in database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Approve_ad();

