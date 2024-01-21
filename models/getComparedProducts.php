<?php
include "../config/connection.php";
if(isset($_GET['ids'])){
    $ids = implode(",", $_GET["ids"]);
    $query = "SELECT p.product_id,p.name,p.discount_id,c.category_name,p.image_src,p.image_alt,pr.price,d.discount_percent,p.in_stock,AVG(r.review_rate) as Rating,COUNT(r.product_id) as NumberOfReviews
    FROM products p 
    INNER JOIN prices pr ON p.product_id = pr.product_id
    LEFT JOIN reviews r ON p.product_id = r.product_id
    INNER JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN discounts d ON p.discount_id = d.discount_id
    WHERE pr.active = 1 AND p.product_id IN($ids)
    GROUP BY p.product_id";
    $result = $conn->query($query)->fetchAll();
    echo json_encode($result);
}