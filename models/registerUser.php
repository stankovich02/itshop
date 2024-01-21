<?php
    require_once '../config/connection.php';
    require_once 'functions.php';
    global $conn;
    if(isset($_POST['submit'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retypedPassword = $_POST['retypedPassword'];
        $username = $_POST['username'];
        $errors = [];
        $reFirstName = "/^[A-ZČĆŽĐŠ][a-zčćžšđ]{2,11}$/";
        $reLastName = "/^[A-ZČĆŽĐŠ][a-zčćžšđ]{2,11}$/";
        $reEmail = "/^[a-z][a-z0-9\.\_]{2,}@[a-z]{2,}(\.[a-z]{2,})+$/";
        $rePassword = "/^(?=.*[A-ZČĆŽĐŠa-zčćžšđ])(?=.*\d)[A-Za-z\d]{8,}$/";
        $reUsername = "/^[A-z0-9]{5,}$/";
        if(!preg_match($reFirstName, $firstName)){
            $errors[] = "First name is not valid! Example: John";
        }
        if(!preg_match($reLastName, $lastName)){
            $errors[] = "Last name is not valid! Example: Smith";
        }
        if(!preg_match($reEmail, $email)){
            $errors[] = "Email is not valid! Example: jhondoe@gmail.com";
        }
        if(!preg_match($rePassword, $password)){
            $errors[] = "Password  must be at least 8 characters long and must contain at least one letter and one number! Example: John1234";
        }
        if($password != $retypedPassword){
            $errors[] = "Passwords do not match!";
        }
        if(!preg_match($reUsername, $username)){
            $errors[] = "Username must be at least 5 characters long and can contain only letter and numbers! Example: John123";
        }
        if(count($errors) == 0){
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                echo "Username already exists!";
                die();
            }
            else{
                $sql = "SELECT * FROM users WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":email", $email);
                $stmt->execute();
                $user = $stmt->fetch();
                if($user){
                    echo "Email already exists!";
                }
                else{
                    $passwordForInsert = md5($password . "itshop");
                    $sql = "INSERT INTO users VALUES( NULL,:firstName, :lastName,:username,:email,:password, NULL, 0, 2,NULL,NULL)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":password", $passwordForInsert);
                    $stmt->bindParam(":firstName", $firstName);
                    $stmt->bindParam(":lastName", $lastName);
                    $stmt->bindParam(":email", $email);
                    try{
                        $stmt->execute();
                        echo "success";
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }
            }
        }
        else{
            foreach($errors as $error){
                echo "$error<br/>";
            }
        }
    }
    else{
        header("Location: ../index.php");
    }
