<?php
namespace dynoser\catshop;

require_once('../../src/config.php');

$conf = new Config();

$sql = "SELECT c.id, c.name, COUNT(p.id) as count FROM `{$conf->table_cat}` c"
    . " LEFT JOIN `{$conf->table_prod}` p"
    . " ON c.id = p.category_id GROUP BY c.id, c.name";

$result = $conf->conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
echo json_encode($categories);
