<?php

require_once '../model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM city WHERE country = " . $_POST['country_id']);
$query->execute();

$cities = $query->fetchAll();

echo "<option value=''>- Select One -</option>";
foreach ($cities as $city) {
    echo "<option $s value='" . $city['id'] . "'>" . $city['name'] . "</option>";
}
