<?php
require_once '../../vendor/autoload.php';
require_once '../../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../../Template/user');
$twig = new Twig\Environment($loader);
echo $twig->render('user-view.twig');
$database = new Database();

$database->loginSession();

?>
