<?php

class Update_Country {

    public function __construct() {
        require_once '../../../templates/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_country();
        if (!array_key_exists("error", $result)) {
            $flash->success("Country Saved successfully", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function save_country() {

        $postData = array(
            'name' => $_POST['name'],
            'id' => $_POST['id'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE country SET `name` = '" . $postData['name'] . "' WHERE id= '" . $postData['id'] . "'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save country to database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Update_Country();

