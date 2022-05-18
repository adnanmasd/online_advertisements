<?php

require_once __DIR__ . '/../model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM categories WHERE inherit = 0");
$query->execute();

$parent = $query->fetchAll();

echo "<option value=''>- Select One -</option>";
foreach ($parent as $cat) {
    echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
}
