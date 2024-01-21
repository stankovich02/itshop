<?php
require_once "../config/setup.php";
require_once "../config/connection.php";
require_once "functions.php";
global $conn;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $table=$_POST['table'];
    $data = $_POST;
    $arrayOfColumns = [];
    foreach ($data as $key => $value){
        if($key != "table"){
            if($key == "password"){
                    array_push($arrayOfColumns, $key);
                    array_push($arrayOfColumns, "avatar");
            }
            else if($key == "image_alt"){
                array_push($arrayOfColumns, "image_src");
                array_push($arrayOfColumns, $key);
            }
            else{
                array_push($arrayOfColumns, $key);
            }
        }
    }
    $string = implode(", ", $arrayOfColumns);
    $sql="INSERT INTO $table($string) VALUES (";
    $image = checkIfImageExists($table);
    if($table == "users" || $table == "products"){
        foreach ($data as $key => $value) {
            if($key == "password"){
                $value = md5($value . "itshop");
                $sql .= "'" . $value . "',";
                $sql .= "'" . $image . "',";
            }
            else if($key == "image_alt"){
                $sql .= "'" . $image . "',";
                $sql .= "'" . $value . "',";
            }
            else{
                if($key != "table"){
                    if ($value != "") {
                        $sql .= "'" . $value . "',";
                    } else {
                        $sql .= "null,";
                    }
                }
            }
        }
    }
    else{
        foreach ($data as $key => $value) 
        {
                if($key != "table"){
                    if($key == "role_id"){
                        $value = 2;
                    }
                    if ($value != "") {
                        $sql .= "'" . $value . "',";
                    } else {
                        $sql .= "null,";
                    }
                }
        }
    }
    $sql=substr($sql,0,-1);
    $sql.=")";
    try {
        $conn->exec($sql);
        echo "You have successfully inserted data into $table table!";
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}