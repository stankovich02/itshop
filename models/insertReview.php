<?php
    require_once '../config/setup.php';
    require_once '../config/connection.php';
    require_once 'functions.php';
    if(isset($_POST['btnReview'])){
        $review = $_POST['message'];
        $rate = $_POST['starNum'];
        $productID = $_POST['productID'];
        $user_id = $_SESSION['user']->user_id;
        $sql = "SELECT * FROM reviews WHERE product_id = :id AND user_id = :userid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $productID);
        $stmt->bindParam(':userid', $user_id);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            echo "You have already reviewed this product!";
        }
        else{
            $sql = "INSERT INTO reviews VALUES (NULL,:productID, :review, :rate, :user_id, current_timestamp)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':review', $review);
            $stmt->bindParam(':rate', $rate);
            $stmt->bindParam(':productID', $productID);
            try {
                $stmt->execute();
                echo "Review added successfully!";
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

    }

