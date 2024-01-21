<?php
require_once "../config/setup.php";
require_once "../config/connection.php";
require_once "functions.php";
global $conn;
if(isset($_POST['updateBtn'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];
    switch ($table){
        case "categories":
            $idColumn = "category_id";
            break;
        case "characteristics":
            $idColumn = "characteristic_id";
            break;
        case "products":
            $idColumn = "product_id";
            break;
        case "prices":
            $idColumn = "price_id";
            break;
        case "discounts":
            $idColumn = "discount_id";
            break;
        case "poll":
            $idColumn = "poll_id";
            break;
        case "messages":
            $idColumn = "message_id";
            break;
        case "orders":
            $idColumn = "order_id";
            break;
        case "users":
            $idColumn = "user_id";
            break;
        case "questions":
            $idColumn = "question_id";
            break;
        case "answers":
            $idColumn = "answer_id";
            break;
        case "reviews":
            $idColumn = "review_id";
            break;
        case "roles":
            $idColumn = "role_id";
            break;
        default:
            break;
    }
    $sql= "SELECT * FROM $table WHERE $idColumn = $id";
    $result= $conn->query($sql)->fetchAll();
    echo json_encode($result);
}