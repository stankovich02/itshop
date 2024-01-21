<?php
    require_once "../config/connection.php";
    global $conn;

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $query = "SELECT * FROM newsletters WHERE email = :email";
    $statement = $conn->prepare($query);
    $statement->bindParam(":email", $email);
    $statement->execute();
    $result = $statement->fetch();
    if($result){
        echo "Email is already subscribed at our newsletter!";
        http_response_code(409);
        exit();
    }
    else{
        $query = "INSERT INTO newsletters VALUES (NULL,:email,NULL)";
        $statement = $conn->prepare($query);
        $statement->bindParam(":email", $email);
        try{
            $statement->execute();
            echo "Email inserted! You will receive our newsletters in future!";
            http_response_code(201);

        }
        catch(PDOException $exception){
            echo "Error: " . $exception->getMessage();
            http_response_code(500);
        }
    }
}