<?php
    require_once "../config/connection.php";
if(isset($_POST["id"])){
    $id = $_POST["id"];
    $query = "UPDATE users SET locked = 0 WHERE user_id = :id";
    $query = $conn->prepare($query);
    $query->bindParam(":id", $id);
    $query->execute();
    if($query){
        http_response_code(200);
        echo "User unbanned successfully!";
    }
    else{
        http_response_code(500);
        echo "Error while unbanning user!";
    }
}
?>