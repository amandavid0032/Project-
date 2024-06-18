<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";

$dbname = "bookxchange";


// $conn = mysqli_connect($servername, $username, $password, $dbname);

$conn = new mysqli($servername, $username, $password, $dbname);

// $admin = new Ajeet\Week6\controller\adminController();
// $admin_m = new Ajeet\Week6\model\adminModel();
?>