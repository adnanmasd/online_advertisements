<?php

class Update_Category {

    public function __construct() {
        require_once '../../../templates/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_category();
        if (!array_key_exists("error", $result)) {
            $flash->success("Category Saved successfully", null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function save_category() {

        $postData = array(
            'name' => $_POST['name'],
            'id' => $_POST['id'],
            'inherit' => $_POST['inherit'],
            'parent_id' => $_POST['parent_id'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            if (!empty($postData['inherit'])) {
                $query = "UPDATE categories  SET parent_id = '" . $postData['parent_id'] . "', `name` = '" . $postData['name'] . "', inherit = '" . $postData['inherit'] . "' 
                     WHERE id = '" . $postData['id'] . "'";
            } else {
                $query = "UPDATE categories SET `name` = '" . $postData['name'] . "' WHERE id= '" . $postData['id'] . "'";
            }
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save category to database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Update_Category();

