<?php
require_once '../../vendor/autoload.php';
require_once '../../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../../Template/admin');
$twig = new Twig\Environment($loader);
$database = new Database();
$table = 'user';
$rows = '*';
$page = "";
$database->loginSession();
$updateid = isset($_POST["id"]) ? $_POST["id"] : null;
$result = $database->select($table, '*', null, $page, "uid = '$updateid'");
$row = $result->fetch_assoc();
echo $twig->render('view-update-user.twig', ['row' => $row]);
?>
