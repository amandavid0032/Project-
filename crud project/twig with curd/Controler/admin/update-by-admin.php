<?php
require_once '../../vendor/autoload.php';
require_once '../../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../../Template/Admin');
$twig = new Twig\Environment($loader);
$database = new Database();
$database->loginSession();
$table = 'user';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $gender = $_POST['gender'];
    $existing_image = $_POST['existing_image'];
    $father_name = $_POST['fathername'];
    $mother_name = $_POST['mothername'];
    $street_no = $_POST['street'];
    $additional_info = $_POST['additional_info'];
    $zip_code = $_POST['zip_code'];
    $place = $_POST['place'];
    $country = $_POST['country'];
    $code = $_POST['code'];
    if ($_FILES['imagename']['name']) {
        $imagename = $_FILES['imagename']['name'];
        move_uploaded_file($_FILES['imagename']['tmp_name'], 'uploads/'.$imagename);
        $result = $database->update($table, array(
            'f_name' => $firstname,
            'l_name' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'image' => $imagename,
            'father_name' => $father_name,
            'mother_name' => $mother_name,
            'street_no' => $street_no,
            'additional_info' => $additional_info,
            'zip_code' => $zip_code,
            'place' => $place,
            'country' => $country,
            'code' => $code,
        ), "uid = '$id'");
        if ($existing_image && file_exists('uploads/' . $existing_image)) {
            unlink('uploads/' . $existing_image);
        }
    } else {
        $result = $database->update($table, array(
            'f_name' => $firstname,
            'l_name' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'father_name' => $father_name,
            'mother_name' => $mother_name,
            'street_no' => $street_no,
            'additional_info' => $additional_info,
            'zip_code' => $zip_code,
            'place' => $place,
            'country' => $country,
            'code' => $code,
        ), "uid = '$id'");
    }

    if ($result !== false) {
        $message = "Your Record For $firstname $lastname Updated successfully";
        $color = 'success';
        header("location:admin-view.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        $message = "Your Record For $firstname Don't Updated ";
        $color = 'danger';
        header("location: admin-view.php?message=" . urlencode($message) . "&color=$color");
        exit();
    }
}
?>
