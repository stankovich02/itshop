<?php
    require_once "../config/setup.php";
    require_once "../config/connection.php";
    require_once "functions.php";
    if(isset($_FILES['avatar'])){
        $errors= array();
        $file_name = $_FILES['avatar']['name'];
        $file_size =$_FILES['avatar']['size'];
        $file_tmp =$_FILES['avatar']['tmp_name'];
        $file_type=$_FILES['avatar']['type'];
        $file_ext_array = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext_array));

        $extensions= array("jpeg","jpg","png");

       if(in_array($file_ext,$extensions)=== false){
            $errors[]="Extension not allowed, please choose a JPEG or PNG file.";
        }

        if($file_size > 2097152){
            $errors[]='File size must be at most 2MB';
        }

        if(empty($errors)==true){
            list($width, $height) = getimagesize($file_tmp);
            if($width < 100 && $height < 100){
                echo "Image is too small!";
                http_response_code(400);
                die();
            }
            else if($width == 100 && $height == 100){
                move_uploaded_file($file_tmp,"../img/userAvatars/". $file_name);
                $picture = $file_name;
            }
            else{
                $imgWidth = 100;
                $imgHeight = 100;
                if($file_type == "image/jpeg" || $file_type == "image/jpg") 
                { 
                    $currentImage = imagecreatefromjpeg($file_tmp);
                }
                else 
                { 
                    $currentImage = imagecreatefrompng($file_tmp);
                }
                $emptyImage = imagecreatetruecolor($imgWidth, $imgHeight);
                imagecopyresampled($emptyImage, $currentImage, 0, 0, 0, 0, $imgWidth, $imgHeight, $width, $height);
                $fileDestination = '../assets/img/userAvatars/' . $file_name;
                if($file_ext == "png"){
                    $newImage = imagepng($emptyImage, $fileDestination);
                }
                else{
                    $newImage = imagejpeg($emptyImage, $fileDestination, 100);
                }
            }
            $picture = $file_name;
            $sql = "UPDATE users SET avatar = CONCAT('img/userAvatars/', :avatar) WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":avatar", $picture);
            $stmt->bindParam(":user_id", $_SESSION['user']->user_id);
            $stmt->execute();
            echo "Success";
            http_response_code(200);
        }else{
            print_r($errors);
        }
    }