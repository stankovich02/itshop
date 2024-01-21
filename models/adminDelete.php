<?php
require_once "../config/setup.php";
require_once "../config/connection.php";
require_once "functions.php";
global $conn;
if(isset($_POST['table'])) {
    $table = $_POST['table'];
        $id = $_POST['id'];
        $idColumn = "";
        $singleRow = "";
        switch ($table){
            case "categories":
                $idColumn = "category_id";
                $singleRow = "Category";
                break;
            case "characteristics":
                $idColumn = "characteristic_id";
                $singleRow = "Characteristic";
                break;
            case "products":
                $idColumn = "product_id";
                $singleRow = "Product";
                break;
            case "prices":
                $idColumn = "price_id";
                $singleRow = "Price";
                break;
            case "discounts":
                $idColumn = "discount_id";
                $singleRow = "Discount";
                break;
            case "poll":
                $idColumn = "poll_id";
                $singleRow = "Poll";
                break;
            case "messages":
                $idColumn = "message_id";
                $singleRow = "Message";
                break;
            case "orders":
                $idColumn = "order_id";
                $singleRow = "Order";
                break;
            case "users":
                $idColumn = "user_id";
                $singleRow = "User";
                break;
            case "questions":
                $idColumn = "question_id";
                $singleRow = "Question";
                break;
            case "answers":
                $idColumn = "answer_id";
                $singleRow = "Answer";
                break;
            case "reviews":
                $idColumn = "review_id";
                $singleRow = "Review";
                break;
            case "roles":
                $idColumn = "role_id";
                $singleRow = "Role";
                break;
            default:
                break;
        }
        $sql = "DELETE FROM ". $table ." WHERE ". $idColumn ." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        try {
            $stmt->execute();
            echo $singleRow . " deleted!";
            http_response_code(200);
        } catch (PDOException $e) {
            echo "Could not delete " . strtolower($singleRow) . "!";
            http_response_code(400);
        }
}
