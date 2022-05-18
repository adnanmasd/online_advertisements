<?php

class New_Category {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->new_category();
        if (!array_key_exists("error", $result)) {
            $flash->success("Category created successfully", null, TRUE);
            header("Location: " . "/ad_site/admin/category/view.php");
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function new_category() {

        $postData = array(
            'name' => $_POST['name'],
            'inherit' => $_POST['inherit'],
            'parent_id' => $_POST['parent_id'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            if (!empty($postData['inherit'])) {
                $query = "INSERT INTO categories (parent_id,`name`,inherit) VALUES
                     ('" . $postData['parent_id'] . "','" . $postData['name'] . "','" . $postData['inherit'] . "')";
            } else {
                $query = "INSERT INTO categories (parent_id,`name`,inherit) VALUES
                     ('0','" . $postData['name'] . "','N')";
            }

            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save ad to database.");
            }
            return ['id' => $link->lastInsertId()];
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new New_Category();

