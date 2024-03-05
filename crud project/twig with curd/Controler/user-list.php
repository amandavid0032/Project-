<?php
require_once '../vendor/autoload.php'; 

$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
echo $twig->render('userlist.html.twig');
?>
