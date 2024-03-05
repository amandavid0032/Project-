<?php
require_once '../vendor/autoload.php';
require_once '../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
$database = new Database();
$table = 'studentrecord';
$rows = '*';
$page = "";
$updateid = isset($_POST["id"]) ? $_POST["id"] : null;
$result = $database->select($table, '*', null, $page, "id = '$updateid'");
$row = $result->fetch_assoc();
echo $twig->render('update-view.twig', ['row' => $row]);
?>
