<?php

class Delete_country {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->delete_country();
        if (!array_key_exists("error", $result)) {
            $flash->success("Country deleted successfully.", null, TRUE);
            header("Location: " . '/ad_site/admin/country/view.php');
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function delete_country() {
        $id = $_GET['id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query_city = $link->prepare("SELECT COUNT(*) AS total FROM city WHERE country = '$id'");
            $query_city->execute();
            $result_city = $query_city->fetch();
            if ($result_city['total'] > 0) {
                throw new Exception("Cannot delete this country as it is assigned to " . $result_city['total'] . " city(ies).");
            }
            $query = "DELETE FROM country WHERE id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't delete country from database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Delete_country();

