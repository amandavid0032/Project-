<?php
require '../../model/user.php';
$database = new Database();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $result = $database->emailCheck($email);
    $response = ($result !== false && count($result) > 0) ? 1 : 0;
    echo $response;
}
