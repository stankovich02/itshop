<?php
require_once 'functions.php';
require_once '../config/connection.php';
global $conn;

if(isset($_GET['name'])){
    $name = $_GET['name'];
    $sql = "SELECT p.name,p.description,p.image_src,c.category_name,p.image_alt,p.in_stock,p.product_id,pr.price,d.discount_id,d.discount_percent,AVG(review_rate) as Rating,COUNT(r.product_id) as NumberOfReviews 
                          FROM products p 
                          INNER JOIN prices pr ON p.product_id = pr.product_id 
                          LEFT JOIN discounts d ON p.discount_id = d.discount_id
                          LEFT JOIN reviews r ON p.product_id = r.product_id
                          INNER JOIN categories c ON p.category_id = c.category_id
                          WHERE pr.active = 1 AND p.name = :name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $product = $stmt->fetch();
    echo json_encode($product);
}