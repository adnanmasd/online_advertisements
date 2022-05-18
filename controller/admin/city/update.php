<?php

class Update_City {

    public function __construct() {
        require_once '../../../templates/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_city();
        if (!array_key_exists("error", $result)) {
            $flash->success("City Saved successfully", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function save_city() {

        $postData = array(
            'name' => $_POST['name'],
            'country' => $_POST['country'],
            'id' => $_POST['id'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE city SET `name` = '" . $postData['name'] . "', country = '" . $postData['country'] . "' WHERE id= '" . $postData['id'] . "'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save city to database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Update_City();

