<?php
require_once '../../vendor/autoload.php';
require_once '../../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../../Template/Admin');
$twig = new Twig\Environment($loader);
echo $twig->render('admin-view.twig');
$database = new Database();
$database->loginSession();

?>

