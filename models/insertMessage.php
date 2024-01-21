<?php
    require_once "../config/connection.php";
    global $conn;
    if(isset($_POST['btnContact'])) {
        $errors = [];
        $regFullName = "/^[A-Z][a-z]{2,14}(\s[A-Z][a-z]{2,14})+$/";
        $regEmail = "/^[a-z][a-z0-9\.\_]{2,}@[a-z]{2,}(\.[a-z]{2,})+$/";
        if (!preg_match($regFullName, $_POST['fullName'])) {
            $errors[] = "Full name is not in the correct format! Example: John Doe";
        }
        if (!preg_match($regEmail, $_POST['email'])) {
            $errors[] = "Email is not in the correct format! Example: johndoe@gmail.com";
        }
        if(strlen($_POST['message']) < 10){
            $errors[] = "Message must contain at least 10 characters!";
        }
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<p id='errorMessage' class='text-danger pt-2 mb-1 font-weight-bold'>$error</p>";
            }
            http_response_code(400);
            die();
        }
        $query = "INSERT INTO messages VALUES (NULL,:fullName,:email,:subject,:message,NULL)";
        $statement = $conn->prepare($query);
        $statement->bindParam(":fullName", $_POST['fullName']);
        $statement->bindParam(":email", $_POST['email']);
        $statement->bindParam(":subject", $_POST['subject']);
        $statement->bindParam(":message", $_POST['message']);
        try {
            $statement->execute();
            echo "<p id='sentMessage'>Message sent!</p>";
            http_response_code(201);
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
            http_response_code(500);
        }
    }
