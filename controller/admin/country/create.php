<?php

class New_Country {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->new_country();
        if (!array_key_exists("error", $result)) {
            $flash->success("Country created successfully", null, TRUE);
            header("Location: " . "/ad_site/admin/country/view.php");
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function new_country() {

        $postData = array(
            'name' => $_POST['name'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "INSERT INTO country (`name`) VALUES
                     ('" . $postData['name'] . "')";

            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save country to database.");
            }
            return ['id' => $link->lastInsertId()];
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new New_Country();

