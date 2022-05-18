<?php

require_once __DIR__ . '/../model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM country");
$query->execute();

$countries = $query->fetchAll();
