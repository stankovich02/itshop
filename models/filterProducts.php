<?php
require_once 'functions.php';
require_once '../config/connection.php';
global $conn;
$sql = "SELECT p.name,p.image_src,p.image_alt,p.in_stock,p.product_id,pr.price,d.discount_id,d.discount_percent,AVG(review_rate) as Rating,COUNT(r.product_id) as NumberOfReviews 
                          FROM products p 
                          INNER JOIN prices pr ON p.product_id = pr.product_id 
                          LEFT JOIN discounts d ON p.discount_id = d.discount_id
                          LEFT JOIN reviews r ON p.product_id = r.product_id
                          WHERE pr.active = 1";
if(isset($_GET['category'])) {
    $category = implode("','", $_GET["category"]);
    $sql .= " AND p.category_id IN('".$category."')
  ";
}
if(isset($_GET['brand'])) {
    $brand = implode("','", $_GET["brand"]);
    $sql .= " AND p.brand_id IN('".$brand."')";
}
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql .= " AND p.name LIKE '%$search%' ";
}
if(isset($_GET['minPrice']) && isset($_GET['maxPrice'])) {
    $minPrice = $_GET['minPrice'];
    $maxPrice = $_GET['maxPrice'];
    $sql .= " AND pr.price BETWEEN " . $minPrice . " AND " . $maxPrice;
}
$sql .= " GROUP BY p.product_id";
if(isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    switch($sort) {
        case 'name-asc':
            $sql .= " ORDER BY p.name ASC";
            break;
        case 'name-desc':
            $sql .= " ORDER BY p.name DESC";
            break;
        case 'price-asc':
            $sql .= " ORDER BY pr.price ASC";
            break;
        case 'price-desc':
            $sql .= " ORDER BY pr.price DESC";
            break;
        case 'newest':
            $sql .= " ORDER BY p.created_at DESC";
            break;
        default:
            $sql .= " ORDER BY p.product_id ASC";
            break;
    }
}
$result = $conn->query($sql);
$productsPrepare = $result->fetchAll();
$total_row = $result->rowCount();
if(isset($_GET['perPage'])){
    $perPage = $_GET['perPage'];
}
else{
    $perPage = 9;
}
if(isset($_GET['viewpage'])){
    if($_GET['viewpage'] == ""){
        $page = 1;
    }
    else{
        $page = (int)$_GET['viewpage'];
    }
    $start = ($page - 1) * $perPage;
    $sql .= " LIMIT $start, $perPage";
}
else{
    $sql .= " LIMIT 0, $perPage";
}
$result = $conn->query($sql);
$products = $result->fetchAll();
$numOfProducts = [
    'numOfProducts' => $total_row
];
array_push($products, $numOfProducts);
$output = '';
echo json_encode($products);
