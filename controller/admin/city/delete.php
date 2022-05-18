<?php

class Delete_city {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->delete_city();
        if (!array_key_exists("error", $result)) {
            $flash->success("City deleted successfully.", null, TRUE);
            header("Location: " . '/ad_site/admin/city/view.php');
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function delete_city() {
        $id = $_GET['id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query_city = $link->prepare("SELECT COUNT(*) AS total FROM `user` WHERE city = '$id'");
            $query_city->execute();
            $result_city = $query_city->fetch();
            if ($result_city['total'] > 0) {
                throw new Exception("Cannot delete this city as it is assigned to " . $result_city['total'] . " user(s).");
            }
            $query = "DELETE FROM city WHERE id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't city from database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Delete_city();

