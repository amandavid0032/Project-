<?php
// session_start();
require_once '../../vendor/autoload.php';
require_once '../../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../../Template/Admin');
$twig = new Twig\Environment($loader);
$database = new Database();
$table = 'user';
$rows = '*';
$limit = 5;
$database->loginSession();
// $loggedInUserId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
$searchValue = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST["page_no"]) ? $_POST["page_no"] : 1;
$sno = ($page - 1) * $limit + 1;
if ($searchValue !== "") {
    $columns = array('f_name');
    $result = $database->search($table, $columns, $searchValue, $limit);
    $total_record = $database->count($table, $columns, $searchValue);
    $total_page = ceil($total_record / $limit);
    echo $twig->render('admin-view-list.twig', ['result' => $result, 'total_page' => $total_page, 'current_page' => $page, 'sno' => $sno, 'searchValue' => $searchValue]);
} else {
    $result = $database->select($table, $rows, $limit, $page,null);
    $total_record = $database->count($table);
    $total_page = ceil($total_record / $limit);
    echo $twig->render('admin-view-list.twig', ['result' => $result, 'total_page' => $total_page, 'current_page' => $page, 'sno' => $sno]);
}


// Delete
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $deleted = $database->deleteRecord($table, $id);
    if ($deleted) {
        echo 1;
    } else {
        echo 0;
    }
}
