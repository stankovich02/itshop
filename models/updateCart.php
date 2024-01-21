<?php
    require_once "../config/setup.php";
    require_once "../config/connection.php";
    require_once "functions.php";
    if(isset($_POST['btnUpdate'])){
        $sql = "UPDATE cart_products SET quantity = :quantity WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':product_id', $_POST['id']);
        try {
            $stmt->execute();
            http_response_code(200);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    else{
        redirect("index.php");
    }
