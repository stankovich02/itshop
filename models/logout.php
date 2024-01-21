<?php
require_once '../config/setup.php';
require_once 'functions.php';

if(isset($_SESSION['user'])) {
    session_destroy();
    redirect('../index.php');
}
else{
redirect('../index.php');
}

