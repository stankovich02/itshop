<?php
    require_once '../config/setup.php';
    require_once '../config/connection.php';
    require_once 'functions.php';
    if(isset($_POST['update'])){
        $userID = $_SESSION['user']->user_id;
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $sql = "SELECT * FROM users_billing_adresses WHERE user_id = $userID";
        $stmt = $conn->query($sql);
        $user = $stmt->fetch();
        if($user){
            $sql = "UPDATE users_billing_adresses SET phone = :phone,address = :address, country = :country, city = :city, zip_code = :zip WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":zip", $zip);
            $stmt->bindParam(":user_id", $userID);
            try{
                $valid = $stmt->execute();
                http_response_code(200);
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }
        else{
            $sql = "INSERT INTO users_billing_adresses (user_id, phone,address, country, city, zip_code) VALUES (:user_id, :phone, :address, :country, :city, :zip)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":user_id", $userID);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":zip", $zip);
            try{
                $valid = $stmt->execute();
                http_response_code(201);
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
