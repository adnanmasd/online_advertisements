<?php

class Db {

    protected static $link;

    public function connect() {
        if (!isset(self::$link)) {
            $config = parse_ini_file($_SERVER ['DOCUMENT_ROOT'] . '/ad_site/model/db/dbconfig.ini');
            $host = $config ['host'];
            $port = $config ['port'];
            $dbname = $config ['dbname'];
            $charset = $config ['charset'];

            try {
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
                $opt = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                self::$link = new PDO($dsn, $config ['username'], $config ['password'], $opt);
            } catch (Exception $e) {
                echo "<br/>DB Error : ". $e->getMessage();
                die();
            }
        }

        if (self::$link === false) {
            return false;
        }
        return self::$link;
    }

}
