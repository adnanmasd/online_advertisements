<?php

require_once 'model/db/db.php';

$db = new Db();
$link = $db->connect();

$query = $link->prepare("SELECT * FROM categories WHERE inherit = 0");
$query->execute();

$parent = $query->fetchAll();
$options = [];
$options[] = "<option value=''>- All Categories -</option>";
foreach ($parent as $cat) {
    $selected = isset($_GET['category']) && $_GET['category'] == $cat['id'] ? 'selected' : '';
    $options[] = "<option $selected value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
    $query = $link->prepare("SELECT * FROM categories WHERE parent_id='" . $cat['id'] . "'");
    $query->execute();

    $child = $query->fetchAll();
    foreach ($child as $c) {
        $selected = isset($_GET['category']) && $_GET['category'] == $c['id'] ? 'selected' : '';
        $options[] = "<option $selected value='" . $c['id'] . "'>" . $cat['name'] . ' > ' . $c['name'] . "</option>";
    }
}
