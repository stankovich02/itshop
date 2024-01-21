<?php
require_once 'config.php';
if(!str_contains($_SERVER["REQUEST_URI"], "admin")){
    logPageAccess();
}
try{
    $conn = new PDO("mysql:host=".SERVER.";dbname=".DATABASE.";charset=utf8", USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
function executeQuery($query){
    global $conn;
    return $conn->query($query)->fetchAll();
}

function logPageAccess() : void{
    $open = fopen("/storage/ssd2/063/20839063/public_html/data/log.txt", "a");
    if($open){
        $currentDate = date("d.m.Y H:i:s");
        fwrite($open, "{$_SERVER["REQUEST_URI"]}\t{$_SERVER['REMOTE_ADDR']}\t$currentDate\n");
        fclose($open);
    }
}