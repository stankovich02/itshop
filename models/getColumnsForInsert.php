<?php
include "../config/connection.php";
global $conn;
if (isset($_GET['insertBtn'])) {
    $table = $_GET['table'];
    $sql = "SHOW COLUMNS FROM $table";
    $result = $conn->query($sql)->fetchAll();
    $columns = [];
    $columnsForSkip = ["created_at", "updated_at", "date_of_sending"];
    foreach ($result as $index=> $value) {
        if($index != 0 && !in_array($value->Field, $columnsForSkip)){
            array_push($columns, $value->Field);
        }
    }
    echo json_encode($columns);
}