<?php

class Delete_category {

    public function __construct() {
        require_once '../../../templates/admin/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->delete_category();
        if (!array_key_exists("error", $result)) {
            $flash->success("Category deleted successfully.", null, TRUE);
            header("Location: " . '/ad_site/admin/category/view.php');
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . $_SERVER['HTTP_REFERER'], true, 307);
        }
    }

    private function delete_category() {
        $id = $_GET['id'];
        try {
            $db = new Db ();
            $link = $db->connect();
            $query_ad = $link->prepare("SELECT COUNT(*) AS total FROM ads WHERE category_id = '$id'");
            $query_ad->execute();
            $result_ad = $query_ad->fetch();
            if ($result_ad['total'] > 0) {
                throw new Exception("Cannot delete this category as it is assigned to " . $result_ad['total'] . " ad(s).");
            }
            $query_cat = $link->prepare("SELECT COUNT(*) AS total FROM categories WHERE parent_id = '$id'");
            $query_cat->execute();
            $result_cat = $query_cat->fetch();
            if ($result_cat['total'] > 0) {
                throw new Exception("Cannot delete this category as it is parent to " . $result_cat['total'] . " category(ies).");
            }

            $query = "DELETE FROM categories WHERE id = '$id'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't delete category from database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Delete_category();

