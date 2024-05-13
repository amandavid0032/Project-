<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../../service/Service.php';
require_once '../pagination.php';

$loader = new Twig\Loader\FilesystemLoader([
    '../../view/admin'
]);
$twig = new Twig\Environment($loader);
$userService = new UserService();
$limit = 5; // Assuming you want 5 records per page

$currentPage = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($currentPage - 1) * $limit;

if (isset($_POST['page'])) {
    $offset = $_POST['page'] - 1; 
    $currentPage = floor($offset / $limit) + 1;
}

$searchValue = isset($_POST['search']) ? $_POST['search'] : '';
$sortSQL = '';

if (!empty($_POST['coltype']) && !empty($_POST['colorder'])) {
    $coltype = $_POST['coltype'];
    $colorder = $_POST['colorder'];
    $sortSQL = " $coltype $colorder";
}
$result = $userService->getUsers($limit, $offset, $searchValue, $sortSQL);
$total_records = $userService->getTotalRecords($searchValue);

// Pagination
$pagination = new Pagination([
    'totalRows' => $total_records,
    'perPage' => $limit,
    'currentPage' => $currentPage,
    'contentDiv' => 'dataContainer',
    'link_func' => 'columnSorting'
]);
$paginationLinks = $pagination->createLinks($result);
// Calculate starting serial number (sno) based on current page
$startSno = ($currentPage - 1) * $limit + 1;

echo $twig->render('adminUserList.twig', [
    'result' => $result,
    'sno' => $startSno,
    'paginationLinks' => $paginationLinks,
    'currentPage' => $currentPage
]);
