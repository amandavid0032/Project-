<?php
session_start();
$userprofile = $_SESSION['email'];
$uid = $_SESSION['uid'];
if ($userprofile == true) {
require_once '../../vendor/autoload.php';
require_once '../../model/user.php';
$loader = new Twig\Loader\FilesystemLoader([
    '../../view/user',
    '../../view/include',
]);
$database = new Database();
$twig = new Twig\Environment($loader);
echo $twig->render('userView.twig',['email'=>$userprofile,'uid'=>$uid]);
}else {
    header('location:../../index.php');
}
?>