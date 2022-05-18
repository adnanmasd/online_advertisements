<?php

class Register {

    public function __construct() {
        require_once '../templates/startSession.php';
        include_once ("../model/db/db.php");
        include '../scripts/flash.php';

        echo "Please Wait ...";
        $flash = new FlashMessages();
        $result = $this->register();
        if (!array_key_exists("error", $result)) {
            $flash->success("User Registered successfully.", null, TRUE);
            $activation = $this->add_activation_row($result['id'], $result['email']);
            $this->send_email($flash, $result['email'], $activation['code']);
            header("Location: " . "/ad_site/home.php");
        } else {
            $flash->error($result['error'], null, TRUE);
            $url = $_GET ['return'];
            header("Location: $url", true, 307);
        }
    }

    private function register() {

        $postData = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'mobile' => $_POST['mobile'],
            'address' => $_POST['address'],
            'country' => (int) $_POST['country'],
            'city' => (int) $_POST['city'],
        );

        try {
            $db = new Db ();
            $link = $db->connect();
            if (!$this->user_exists($link, $postData['email'])) {
                $query = "INSERT INTO `user`(firstname,lastname,email,password,mobile_number,address,city,country,status,date_registered) "
                        . "VALUES ('" . $postData['firstname'] . "' ,'" . $postData['lastname'] . "' , '" . $postData['email'] . "' ,
                        '" . $postData['password'] . "' , '" . $postData['mobile'] . "' ,'" . $postData['address'] . "' ,'" . $postData['city'] . "' ,'" . $postData['country'] . "' , '0' , now())";
                $result = $link->query($query);
                if (!$result) {
                    throw new Exception("Coudn't save user to database.");
                }
                return ['id' => $link->lastInsertId(), 'email' => $postData['email']];
            } else {
                throw new Exception("User already exists. Please use different email.");
            }
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    private function user_exists($link, $email) {
        $stmt = $link->query("SELECT * FROM `user` WHERE email='" . $email . "'");
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r;
    }

    private function add_activation_row($id, $email) {
        try {
            $db = new Db();
            $link = $db->connect();
            $code = sha1(rand(1000, 9999) . $email . chr(rand(65, 90)) . $id . rand(9999, 99999));
            $query = "INSERT INTO user_activation (`userId`,`verificationCode`,date_generated) VALUES ('$id','$code',now())";
            $result = $link->query($query);
            if (!$result) {
                throw new Exception("Coudn't save user to database.");
            }
            return ['code' => $code];
        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    private function send_email($flash, $email, $code) {
        $to = $email;
        $subject = "Account Activation";
        $message = "<html><body><h4>Greetings,</h4><br/><p>Please click on the link below to activate your account in Ad Site.</p>"
                . "<a href='http://localhost:8080/ad_site/controller/activate.php?code=$code'>Activate My Account</a></body></html>";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: Ad Site <noreply@adsite.com>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $isMailSent = mail($to, $subject, $message, $headers);
        if ($isMailSent) {
            $flash->warning("An Email has been sent to you with the activation. Please click on the link to activate and use your account", null, TRUE);
        } else {
            $flash->error("Activation Email not sent");
        }
        //$flash->warning("An Email has been sent to you with the activation. Please click on the link to activate and use your account", null, TRUE);
        //$flash->info('<strong>Email Content: </strong> <br/><br/>' . $message);
    }

}

new Register();

