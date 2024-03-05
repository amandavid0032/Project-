<?php
require 'model/main-db.php';
use mainclass as main;
$database = new \main\Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = 'studentrecord';
    $values = array(
        'userimage' => $_FILES['imagename']['name'],
        'f_name' => $_POST['firstname'],
        'l_name' => $_POST['lastname'],
        'age' => $_POST['age'],
        'emailId' => $_POST['email'],
        'phone' => $_POST['phone'],
        'gender' => $_POST['gender']
    );
    var_dump($values);
    $insertId = $database->insert($table, $values);
    if ($insertId) {
        $message = 'Your Record Added successfully';
        $color = 'success';
        header("location: user-list.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
       echo "error";
    }
}
?>