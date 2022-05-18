<?php

require_once '../model/db/db.php';

$db = new Db ();
$link = $db->connect();

$keyword = strtolower(trim($_GET ['term']));

$query = $link->query("SELECT a.*,c.`name` as category FROM `ads` a LEFT JOIN categories c ON c.id = a.category_id WHERE a.`title` LIKE '%" . $keyword . "%' AND a.status='1' ORDER BY a.`title` ASC LIMIT 10");

$data = [];
$entry['keyword'] = $keyword;
$entry['category'] = "All Categories";
$entry['cat'] = null;
$data[] = $entry;

while ($row = $query->fetch()) {
    $entry['keyword'] = $row['title'];
    $entry['category'] = $row['category'];
    $entry['cat'] = $row['category_id'];
    $data[] = $entry;
}

// return json data
echo json_encode($data);
?>