<?php

class Save_Profile {

    public function __construct() {
        require_once '../templates/startSession.php';
        include_once ("../model/db/db.php");
        include '../scripts/flash.php';

        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->save_profile();
        if (!array_key_exists("error", $result)) {
            $flash->success("New Information saved to database successfully.", null, TRUE);
            header("Location: " . "/ad_site/edit_profile.php");
        } else {
            $flash->error($result['error'], null, TRUE);
            header("Location: " . "/ad_site/edit_profile.php", true, 307);
        }
    }

    private function save_profile() {

        $postData = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile'],
            'address' => $_POST['address'],
            'country' => (int) $_POST['country'],
            'city' => (int) $_POST['city'],
            'user_id' => $_POST['user_id']
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            $query = "UPDATE `user` SET firstname = '" . $postData['firstname'] . "' ,"
                    . "lastname = '" . $postData['lastname'] . "', "
                    . "mobile_number = '" . $postData['mobile'] . "' ,"
                    . "address = '" . $postData['address'] . "',"
                    . "city = '" . $postData['city'] . "',"
                    . "country = '" . $postData['country'] . "' "
                    . "WHERE id='" . $postData['user_id'] . "'";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save imformation to database.");
            }
            return;
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

}

new Save_Profile();

