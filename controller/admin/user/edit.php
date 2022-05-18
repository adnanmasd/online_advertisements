<?php

class Save_User {

    public function __construct() {
        require_once '../templates/startSession.php';
        include_once ("../model/db/db.php");
        include '../scripts/flash.php';
        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_user();
        if (!array_key_exists("error", $result)) {
            $flash->success("User Saved successfully.", null, TRUE);
            header("Location: " . "/ad_site/admin/user/edit.php?id=" . $_POST['id']);
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . "/ad_site/admin/user/edit.php?id=" . $_POST['id'], true, 307);
        }
    }

    private function save_user() {

        $postData = array(
            'id' => $_POST['id'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'mobile_number' => $_POST['mobile_number'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'country' => $_POST['country']
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE `user` SET 
                    firstname = '" . $postData['firstname'] . "',
                    lastname = '" . $postData['lastname'] . "',
                    email = '" . $postData['email'] . "',
                    mobile_number = '" . $postData['mobile_number'] . "',
                    address = '" . $postData['address'] . "',
                    city = '" . $postData['city'] . "',
                    country = '" . $postData['country'] . "',
                    WHERE id = '" . $postData['id'] . "'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save user to database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Save_User();

