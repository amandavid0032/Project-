<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'model/user.php';

// Check if user is already logged in
if (isset($_SESSION['email'])) {
    if ($_SESSION['type'] == 1) {
        header("Location: controler/admin/adminView.php");
        exit();
    } elseif ($_SESSION['type'] == 0) {
        header("Location: controler/user/userView.php");
        exit();
    }
}

$loader = new Twig\Loader\FilesystemLoader('view');
$twig = new Twig\Environment($loader);
$database = new dataBase();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $result = $database->loginUser($email, $password);
    if (!empty($result)) {
        $_SESSION['email'] = $result['email'];
        $_SESSION['type'] = $result['type'];
        $_SESSION['uid'] = $result['uid'];
        if ($result['type'] == 1) {
            header("Location: controler/admin/adminView.php");
            exit();
        } elseif ($result['type'] == 0) {
            header("Location: controler/user/userView.php");
            exit();
        }
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
echo $twig->render('login.twig', ['error' => $error]);
