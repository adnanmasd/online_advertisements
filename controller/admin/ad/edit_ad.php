<?php

class Save_Ad {

    const UPLOAD_DIR = "/ad_site/web/ad_images/";

    public function __construct() {
        require_once '../../../templates/startSession.php';
        include_once ("../../../model/db/db.php");
        include '../../../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_ad();
        if (!array_key_exists("error", $result)) {
            $flash->success("Ad Saved successfully.", null, TRUE);
            header("Location: " . "/ad_site/admin/ad/edit.php?ad_id=" . $_POST['ad_id']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . "/ad_site/admin/edit_ad.php?id=" . $_POST['ad_id'], true, 307);
        }
    }

    private function save_ad() {

        $postData = array(
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'condition' => $_POST['condition'],
            'parent' => $_POST['parent'],
            'child' => $_POST['child'],
            'user_id' => $_SESSION['user']['id'],
            'id' => $_POST['ad_id']
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE ads  SET 
                    category_id = '" . $postData['child'] . "',
                    title = '" . $postData['title'] . "',
                    description = '" . $postData['description'] . "',
                    price = '" . $postData['price'] . "',
                    `condition` = '" . $postData['condition'] . "'"
                    . " WHERE id = '" . $postData['id'] . "' AND `userId` = '" . $postData['user_id'] . "'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save ad to database.");
            }
            $images = $this->save_images($postData['id']);
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    private function save_images($id) {
        $flash = new FlashMessages();
        $files = $_FILES;
        if (!empty($files)) {
            $db = new Db();
            $link = $db->connect();
            $target_dir = $this::UPLOAD_DIR . $id;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $target_dir)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $target_dir, 0777, true);
            }
            foreach ($files as $key => $file) {
                if (!empty($file) && $file['error'] == UPLOAD_ERR_OK) {
                    $target_file = $target_dir . '/' . $key . '_' . basename($files[$key]["name"]);
                    $imageFileType = pathinfo($_SERVER['DOCUMENT_ROOT'] . $target_file, PATHINFO_EXTENSION);
                    $uploadOk = 1;
                    // Check if image file is a actual image or fake image
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $check = getimagesize($files[$key]["tmp_name"]);
                        if ($check !== false) {
                            echo ("File is an image - " . $check["mime"] . ".");
                            $uploadOk = 1;
                        } else {
                            $flash->error("File is not an image.");
                            $uploadOk = 0;
                        }
                    }
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        $flash->error("Sorry, " . $files[$key]["name"] . " already exists.");
                        $uploadOk = 0;
                    }
                    // Check file size
                    if ($files[$key]["size"] > 2100000) {
                        $flash->error("Sorry, " . $files[$key]["name"] . " is too large.");
                        $uploadOk = 0;
                    }
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
                        $flash->error("Sorry " . $files[$key]["name"] . " , only JPG, JPEG files are allowed.");
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        $flash->error("Sorry, " . $files[$key]["name"] . " was not uploaded.");
                        // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($files[$key]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
                            $query = "UPDATE ads SET `$key` = '$target_file' WHERE id = '$id'";
                            $result = $link->query($query);
                            echo "The file " . basename($_FILES[$key]["name"]) . " has been uploaded.";
                        } else {
                            $flash->error("Sorry, there was an error uploading " . $files[$key]["name"]);
                        }
                    }
                }
            }
        }
    }

}

new Save_Ad();

