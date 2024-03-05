<?php
require_once '../vendor/autoload.php';
require_once '../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
$database = new Database();
$table = 'studentrecord';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $existing_image = $_POST['existing_image'];
    if ($_FILES['imagename']['name']) {
        $imagename = $_FILES['imagename']['name'];
        move_uploaded_file($_FILES['imagename']['tmp_name'], 'uploads/' . $imagename);
        $result = $database->update($table, array(
            'f_name' => $firstname,
            'l_name' => $lastname,
            'age' => $age,
            'emailId' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'userimage' => $imagename
        ), "id = '$id'");
        if ($existing_image && file_exists('uploads/' . $existing_image)) {
            unlink('uploads/' . $existing_image);
        }
    } else {
        $result = $database->update($table, array(
            'f_name' => $firstname,
            'l_name' => $lastname,
            'age' => $age,
            'emailId' => $email,
            'phone' => $phone,
            'gender' => $gender
        ), "id = '$id'");
    }
    if ($result !== false) {
        $message = "Your Record For $firstname $lastname Updated successfully";
        $color = 'success';
        header("location: user-list.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        $message = "Your Record For $firstname Don't Updated ";
        $color = 'danger';
        header("location: user-list.php?message=" . urlencode($message) . "&color=$color");
        exit();
    }
}
?>