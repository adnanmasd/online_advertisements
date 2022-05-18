<?php

class Reject_ad {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->reject_ad();
        if (!array_key_exists("error", $result)) {
            $flash->success("Ad Rejected successfully.", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function reject_ad() {
        $id = $_GET['ad_id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE ads a SET a.status = '2', a.date_approved = NULL WHERE id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't reject ad in database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Reject_ad();

