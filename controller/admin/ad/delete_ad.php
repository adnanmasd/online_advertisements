<?php

class Delete_ad {

    const UPLOAD_DIR = "/ad_site/web/ad_images/";

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->delete_ad();
        if (!array_key_exists("error", $result)) {
            $flash->success("Ad deleted successfully.", null, TRUE);
            header("Location: " . '/ad_site/admin/ad/all.php');
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function delete_ad() {
        $id = $_GET['ad_id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            if (!$this->delete_images($id)) {
                $query = "DELETE FROM ads WHERE id = '$id'";
                $result = $link->query($query);
                if (!$result) {
                    throw new Exception("Coudn't delete ad from database.");
                }
                return;
            } else {
                throw new Exception("Failed while deleting images. Please try again");
            }
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }
    
    private function delete_images($id) {
        $dir = $_SERVER['DOCUMENT_ROOT'] . $this::UPLOAD_DIR . $id . '/';
        if (is_dir($dir)) {
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                } else {
                    unlink($dir.$item);
                }
            }
            return rmdir($dir);
        }
    }

}

new Delete_ad();

