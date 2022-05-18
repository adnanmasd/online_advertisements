<?php

require_once '../model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM categories WHERE parent_id = " . $_POST['parent_id']);
$query->execute();

$child = $query->fetchAll();

echo "<option value=''>- Select One -</option>";
foreach ($child as $cat) {
    echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
}
