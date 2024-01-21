<?php
require_once 'functions.php';
require_once '../config/connection.php';
global $conn;
$sql = "SELECT p.name,p.brand_id,p.category_id,p.image_src,p.image_alt,p.in_stock,p.product_id,pr.price,d.discount_id,d.discount_percent,AVG(review_rate) as Rating,COUNT(r.product_id) as NumberOfReviews 
                          FROM products p 
                          INNER JOIN categories c ON c.category_id = p.category_id
                          INNER JOIN brands b ON b.brand_id = p.brand_id
                          INNER JOIN prices pr ON p.product_id = pr.product_id 
                          LEFT JOIN discounts d ON p.discount_id = d.discount_id
                          LEFT JOIN reviews r ON p.product_id = r.product_id
                          WHERE pr.active = 1";
$sql .= " GROUP BY p.product_id";
$result = $conn->query($sql);
$productsPrepare = $result->fetchAll();
echo json_encode($productsPrepare);