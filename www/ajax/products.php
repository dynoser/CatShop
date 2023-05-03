<?php
namespace dynoser\catshop;

require_once('../../src/Config.php');

$conf = new Config();

$sql = "SELECT * FROM `{$conf->table_prod}`";

// get user parameter:
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

if ($category_id) {
    if (!is_numeric($category_id)) {
        die("category_id must be numeric");
    }
    $sql .= " WHERE category_id=" . $category_id;
} else {
    // List products if category_id not specified
    $sql .= " LIMIT 50";
}

// do request
$result = $conf->conn->query($sql);

// get resutls to array
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// pack to JSON and return
echo json_encode($products);

