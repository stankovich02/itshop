<?php
    require_once '../config/setup.php';
    require_once '../config/connection.php';
    require_once 'functions.php';
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $errors = [];
        $reUsername = "/^[A-z0-9]{5,}$/";
        $rePassword = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
        if(!preg_match($reUsername, $username)){
            $errors[] = "Invalid username!";
        }
        if(!preg_match($rePassword, $password)){
            $errors[] = "Invalid password!";
        }
        if(count($errors) == 0){
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch();
            $passwordForCheck = md5($password . "itshop");
            if($user){
                if($user->password == $passwordForCheck && $user->locked == 0){
                    $_SESSION['user'] = $user;
                    if($user->role_id == 2){
                    $file = fopen("../data/logins.txt", "a");
                    $currentDate = date("d.m.Y");
                    fwrite($file, $username . " : " . $currentDate . "\n");
                    fclose($file);
                    }
                    echo "success";
                    http_response_code(200);
                }
                else if($user->locked == 1){
                    http_response_code(404);
                    echo "Account is locked!";
                    
                }
                else{
                    http_response_code(404);
                    echo "Wrong password!";
                    $timeBeforeFiveMinutes = date('Y-m-d H:i:s', time() - 300);
                    $file = file("../data/attemptedLogins.txt");
                    $todaysDateTime = date('Y-m-d H:i:s');
                    $string = $username . "\t" . $todaysDateTime . "\t" . 1 . "\n";
                    if(count($file) == 0){
                        $file[] = $string;
                        file_put_contents("../data/attemptedLogins.txt", $file);
                        http_response_code(404);
                        die();
                    }
                    $newFile = [];
                    $hasUser = false;
                    foreach($file as $line){
                        list($usernameFromFile,$datetime,$attempts) = explode("\t",trim($line));
                        if($usernameFromFile == $username){
                            $hasUser = true;
                            $attempts++;
                            if($attempts == 3 && strtotime($todaysDateTime) - strtotime($datetime) < 300){
                                $sql = "UPDATE users SET locked = 1 WHERE username = :username";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(":username", $username);
                                $stmt->execute();
                                mail($user->email, "Account locked", "Your account has been locked because of too many failed login attempts!");
                                http_response_code(404);
                                die();
                            }
                            else{
                                if(strtotime($todaysDateTime) - strtotime($datetime) > 300){
                                    $attempts = 1;
                                }
                            }
                            $newFile[] = $username . "\t" . gmdate('Y-m-d H:i:s') . "\t" . $attempts . "\n";
                        }
                        else{
                            $newFile[] = $line;
                        }
                    }
                    if(!$hasUser){
                        $newFile[] = $string;
                    }
                    file_put_contents("../data/attemptedLogins.txt", $newFile);
                    http_response_code(404);
                }
            }
            else{
                http_response_code(404);
                echo "User does not exist!";
                
            }
        }
        else{
            http_response_code(404);
            foreach($errors as $error){
                echo "$error<br/>";
                
            }
           
        }
    }
