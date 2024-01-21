<?php
    require_once '../config/setup.php';
    require_once '../config/connection.php';
    require_once 'functions.php';
    if(isset($_POST['btnOrder'])){
        $user = $_SESSION['user']->user_id;
        $total = $_POST['totalPrice'];
        $payment = $_POST['paymentID'];
        $sql = "INSERT INTO orders (order_id, user_id, total_price, payment_id) VALUES (NULL, :user_id, :total_price, :payment_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user);
        $stmt->bindParam(":total_price", $total);
        $stmt->bindParam(":payment_id", $payment);
        try{
            $valid = $stmt->execute();
            if($valid){
                $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":user_id", $user);
                $stmt->execute();
                $order = $stmt->fetch();
                $orderID = $order->order_id;
                echo $orderID;
                http_response_code(201);
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    else{
        http_response_code(400);
        redirect('../index.php');
    }
