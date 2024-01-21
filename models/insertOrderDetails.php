<?php
    require_once '../config/setup.php';
    require_once '../config/connection.php';
    require_once 'functions.php';
    if(isset($_POST['productID'])) {
        $sql = GET_ALL_PRODUCTS . " AND p.product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":product_id", $_POST['productID']);
        $product = $stmt->execute();
        $product = $stmt->fetch();
        $activePrice = $product->discount_id == null ? $product->price : getProductDiscountedPrice($product);
        $orderID = $_POST['orderID'];
        $quantity = $_POST['quantity'];
        $sql = "INSERT INTO order_items (order_item_id, order_id, product_id, quantity, product_price) VALUES (NULL, :order_id, :product_id, :quantity, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":order_id", $orderID);
        $stmt->bindParam(":product_id", $_POST['productID']);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":price", $activePrice);
        try{
            $valid = $stmt->execute();
            if($valid){
                if(isset($_POST['lastProduct'])){
                    $sql = "DELETE FROM cart_products WHERE cart_id = 
                    (SELECT cart_id FROM cart WHERE user_id = :user_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":user_id", $_SESSION['user']->user_id);
                    $stmt->execute();
                }
                http_response_code(201);

            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
