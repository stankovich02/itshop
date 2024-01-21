<?php
require_once "../config/setup.php";
require_once "../config/connection.php";
require_once "functions.php";
global $conn;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $data=$_POST;
    $table=$_POST['table'];
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
    $id=$_POST[$idColumn];
    $image = checkIfImageExists($table);
    $query = "UPDATE $table SET ";
    if($table == "users" || $table == "products"){
        foreach ($data as $key => $value) {
            if($key != $idColumn && $key != "table"){
                if($key == "password"){
                    $oldValue = checkUserPassword($_POST['user_id']);
                    if($oldValue != md5($value)){
                        $value = md5($value . "itshop");
                    }
                    $query .= "$key = '$value', ";
                    $query .= "avatar = '" . $image . "', ";
                }
                else if($key == "image_alt"){
                    $query .= "image_src = '" . $image . "', ";
                    $query .= "$key = '$value', ";
                }
                else{
                    if ($value != "") {
                        $query .= "$key = '$value', ";
                    } else {
                        $query .= "$key = null, ";
                    }
                }
            }
        }
    }
    $query = rtrim($query, ", ");
    $first_key = array_key_first($data);
    $query .= " WHERE $idColumn = :id";
    $result = $conn->prepare($query);
    $result->bindParam(":id", $id);
    try {
        $result->execute();
        echo "You have successfully updated the $table table!";
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}