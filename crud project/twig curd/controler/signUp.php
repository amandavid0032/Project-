<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../service/fileuplode.php';
$loader = new Twig\Loader\FilesystemLoader('../view');
$twig = new Twig\Environment($loader);
$database = new Database();
$fileuploade = new file();
$error = '';
if (isset($_SESSION['email'])) {
    header("Location: user/userView.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageNames = $fileuploade->uploadImages($_FILES);
    if (empty($error)) {
        $userData = array(
            'f_name' => $_POST['firstname'],
            'l_name' => $_POST['lastname'],
            'father_name' => $_POST['fathername'],
            'mother_name' => $_POST['mothername'],
            'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password']),
            'street_no' => $_POST['street'],
            'additional_info' => $_POST['additional_info'],
            'zip_code' => $_POST['zip_code'],
            'place' => $_POST['place'],
            'country' => $_POST['country'],
            'code' => $_POST['code'],
            'phone' => $_POST['phone_number'],
            'type' => $_POST['role'],
            'image' => implode(",", $imageNames),
        );
        $email = $_POST['email'];
        $role=$_POST['role'];
        $existingUser = $database->getUserByEmail($email);
        if ($existingUser) {
            $error = 'Email already exists';
        } else {
            $insertId = $database->registerUser($userData);
            if ($insertId) {
                $_SESSION['email'] = $email;
                $_SESSION['uid']=$insertId;
                if ($role==1) {
                    header("Location: admin/adminView.php");
                } else {
                    header("Location: user/userView.php");
                    exit();
                }
            } else {
                $error = "Your record couldn't be added";
            }
        }
    }
}
echo $twig->render('signUp.twig', ['error' => $error]);
