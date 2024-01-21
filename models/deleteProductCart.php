<?php
require_once "../config/setup.php";
require_once "../config/connection.php";
global $conn;
if(isset($_POST['btnDelete'])){
    $sql = "SELECT * FROM cart_products cp 
        INNER JOIN cart c ON c.cart_id = cp.cart_id
        WHERE cp.product_id = :product_id AND c.user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $_POST['id']);
    $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
    $stmt->execute();
    $total_row = $stmt->rowCount();
    if($total_row > 0)
    {
        $sql = "DELETE FROM cart_products 
            WHERE product_id = :product_id AND cart_id = 
            (SELECT cart_id FROM cart WHERE user_id = :user_id )";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $_POST['id']);
        $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
        $stmt->execute();
        echo "Product removed from your cart!";
        http_response_code(200);
    }
    else
    {
        echo "Product not in your cart!";
        http_response_code(404);
    }
}
else{
    http_response_code(401);
}
