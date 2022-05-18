<?php

class New_City {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->new_city();
        if (!array_key_exists("error", $result)) {
            $flash->success("City created successfully", null, TRUE);
            header("Location: " . "/ad_site/admin/city/view.php");
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function new_city() {

        $postData = array(
            'name' => $_POST['name'],
            'country' => $_POST['country'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "INSERT INTO city (`name`,country) VALUES
                     ('" . $postData['name'] . "', '" . $postData['country'] . "')";

            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save city to database.");
            }
            return ['id' => $link->lastInsertId()];
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new New_City();

