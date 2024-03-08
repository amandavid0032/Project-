<?php
require_once '../vendor/autoload.php'; 
require_once '../model/main-db.php'; 
$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
$table = 'user';
$database = new Database();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $hashed_password = md5($password);
    $value = array(
        'f_name' => $_POST['firstname'],
        'l_name' => $_POST['lastname'],
        'father_name' => $_POST['fathername'],
        'mother_name' => $_POST['mothername'],
        'gender' => $_POST['gender'],
        'email' => $_POST['email'],
        'password' => $hashed_password,
        'street_no' => $_POST['street'],
        'additional_info' => $_POST['additional_info'],
        'zip_code' => $_POST['zip_code'],
        'place' => $_POST['place'],
        'country' => $_POST['country'],
        'code' => $_POST['code'],
        'phone' => $_POST['phone_number'],
        'image' => $_FILES['imagename']['name'],
    );
    $insertId = $database->insert($table, $value);
    if ($insertId) {
        $message = 'Your Record Added successfully';
        $color = 'success';
        header("location: admin/admin-view.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        $message = "Your Record Don't add ";
        $color = 'danger';
        header("location: sign-up.php?message=" . urlencode($message) . "&color=$color");
        exit();
    }
}
echo $twig->render('sign-up.twig');

?>
