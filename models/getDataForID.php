<?php
include "../config/connection.php";
global $conn;
if(isset($_GET['getData'])) {
    $table = $_GET['table'];
    switch ($table){
        case "category":
            $table = "categories";
            break;
        case "brand":
            $table = "brands";
            break;
        case "characteristic":
            $table = "characteristics";
            break;
        case "product":
            $table = "products";
            break;
        case "price":
            $table = "prices";
            break;
        case "discount":
            $table = "discounts";
            break;
        case "poll":
            $table = "poll";
            break;
        case "message":
            $table = "messages";
            break;
        case "order":
            $table = "orders";
            break;
        case "user":
            $table = "users";
            break;
        case "question":
            $table = "questions";
            break;
        case "answer":
            $table = "answers";
            break;
        case "review":
            $table = "reviews";
            break;
        case "role":
            $table = "roles";
            break;
        default:
            break;
    }
    if($table == "characteristics"){
        $sql= "SELECT * FROM $table WHERE name IS NOT NULL";
    }
    else{
        $sql= "SELECT * FROM $table";
    }
    $result = $conn->query($sql)->fetchAll();
    echo json_encode($result);
}
