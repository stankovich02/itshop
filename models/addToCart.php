<?php
    require_once "../config/setup.php";
    require_once "../config/connection.php";
    require_once "functions.php";
    global $conn;
    if(isset($_SESSION['user'])){
    if(isset($_POST['btnCart'])){
        $total_rowUser = UserExistsInCartOrWish($_SESSION['user']->user_id, "cart");
        $total_row = 0;
        if($total_rowUser == 0){
            insertUserIntoCartOrWish($_SESSION['user']->user_id, "cart");
        }
        else{
            $total_row = UserAlreadyHaveProductInCart($_POST['id'],$_SESSION['user']->user_id);
        }

        if($total_row == 0)
        {
            $sql = "SELECT cart_id FROM cart WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
            $stmt->execute();
            $userCart_id = $stmt->fetch()->cart_id;
            $sql = "INSERT INTO cart_products (cart_id,product_id,quantity) VALUES (:cart_id, :product_id, :quantity)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cart_id', $userCart_id);
            $stmt->bindParam(':product_id', $_POST['id']);
            $stmt->bindParam(':quantity', $_POST['quantity']);
            try {
                $stmt->execute();
                echo "Product added to your cart!";
                http_response_code(200);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        else
        {
            echo "Product already in your cart!";
            http_response_code(409);
        }
    }
    else{
        redirect("index.php");
    }
    }
    else{
        echo "You must be logged in to add product to cart!";
        http_response_code(401);
    }