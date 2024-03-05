<?php
require_once '../vendor/autoload.php'; 
require_once '../model/main-db.php'; 
$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
echo $twig->render('index.twig');
$database = new Database();
$table='studentrecord';
// From submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = 'studentrecord';
    $values = array(
        'userimage' => $_FILES['imagename']['name'],
        'f_name' => $_POST['firstname'],
        'l_name' => $_POST['lastname'],
        'age' => $_POST['age'],
        'emailId' => $_POST['email'],
        'phone' => $_POST['phone'],
        'gender' => $_POST['gender']
    );
    $insertId = $database->insert($table, $values);
    if ($insertId) {
        $message = 'Your Record Added successfully';
        $color = 'success';
        header("location: user-list.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
       echo "error";
    }
}
?>
