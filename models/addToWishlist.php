<?php
    require_once "../config/setup.php";
    require_once "../config/connection.php";
    require_once "functions.php";
    global $conn;
    if(isset($_SESSION['user'])){
        if(isset($_POST['id'])){
            $total_rowUser = UserExistsInCartOrWish($_SESSION['user']->user_id, "wishlist");
            $total_row = 0;
            if($total_rowUser == 0){
                insertUserIntoCartOrWish($_SESSION['user']->user_id, "wishlist");
            }
            else{
                $total_row = UserAlreadyHaveProductInWishlist($_POST['id'],$_SESSION['user']->user_id);
            }

            if($total_row == 0)
            {
                $sql = "SELECT wishlist_id FROM wishlist WHERE user_id = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $_SESSION['user']->user_id);
                $stmt->execute();
                $userWishlist_id = $stmt->fetch()->wishlist_id;
                $sql = "INSERT INTO wishlist_products (wishlist_id, product_id) VALUES (:wishlist_id, :product_id)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':wishlist_id', $userWishlist_id);
                $stmt->bindParam(':product_id', $_POST['id']);
                try {
                    $stmt->execute();
                    echo "Product added to your wishlist!";
                    http_response_code(200);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
            else
            {
                echo "Product already in your wishlist!";
                http_response_code(409);
            }
        }
        else{
            redirect("index.php");
        }
    }
    else{
        echo "You must be logged in to add product to wishlist!";
        http_response_code(401);
    }
