<?php
require_once 'classes/Database.php';

$database = new Database();
$pdo = $database->getConnection();
?>
