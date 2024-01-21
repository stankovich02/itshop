<?php
    require_once "../config/setup.php";
    require_once "../config/connection.php";
    if(isset($_POST['btnDelete'])){
        $sql = "SELECT * FROM wishlist_products wp 
        INNER JOIN wishlist w ON w.wishlist_id = wp.wishlist_id
        WHERE wp.product_id = :product_id AND w.user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $_POST['id']);
        $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
        $stmt->execute();
        $total_row = $stmt->rowCount();
        if($total_row > 0)
        {
            $sql = "DELETE FROM wishlist_products 
            WHERE product_id = :product_id AND wishlist_id = 
            (SELECT wishlist_id FROM wishlist WHERE user_id = :user_id )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_id', $_POST['id']);
            $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
            $stmt->execute();
            echo "Product removed from your wishlist!";
            http_response_code(200);
        }
        else
        {
            echo "Product not in your wishlist!";
            http_response_code(404);
        }
    }
    else{
        http_response_code(401);
    }
