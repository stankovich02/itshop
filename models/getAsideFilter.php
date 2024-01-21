<?php
require_once 'functions.php';
require_once '../config/connection.php';
global $conn;

if(isset($_GET['table'])){
    $table = $_GET['table'];
    if($table == "categories"){
         $sql = "SELECT COUNT(c.category_id) as NumOfProducts,c.category_id ,c.category_name FROM categories c INNER JOIN products p ON c.category_id = p.category_id
         GROUP BY c.category_id";
    }
     if($table == "brands"){
         $sql = "SELECT COUNT(b.brand_id) as NumOfProducts,b.brand_id, b.brand_name FROM brands b INNER JOIN products p ON b.brand_id = p.brand_id
         GROUP BY b.brand_id";
    }
    $json = $conn->query($sql)->fetchAll();
    echo json_encode($json);
    }