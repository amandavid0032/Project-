<?php
require_once '../../vendor/autoload.php';
require_once '../../model/user.php';
require_once '../../service/service.php';
require_once '../../service/fileuplode.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('../../view/user');
$twig = new Environment($loader);
$userService = new UserService();
$fileuplode = new file();

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
    $imageNames = array();
    if (!empty($_FILES['image']['name'][0])) {
        $imageNames = $fileuplode->uploadImages($_FILES);

        if ($imageNames === false) {
            $error = 'Failed to upload some files.';
            echo $error;
            exit();
        }
    } else {
        $imageNames[] = $existing_image;
    }
    $update_data = array(
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
        'image' => implode(",", $imageNames),
    );
    $result = $userService->updateUser($id, $update_data);
    if ($result && $existing_image && file_exists('../../uploads/' . $existing_image)) {
        unlink('../../uploads/' . $existing_image);
    }
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
    exit();
}
