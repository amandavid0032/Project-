<?php
require_once '../../vendor/autoload.php';
require_once '../../model/user.php';
$loader = new Twig\Loader\FilesystemLoader('../../view/admin');
$twig = new Twig\Environment($loader);
$database = new Database();
if (isset($_POST['id'])) {
$id = $_POST['id'];
$deleted = $database->deleteUser($id);
if ($deleted) {
echo 1;
} else {
echo 0;
}
exit;
}

?>