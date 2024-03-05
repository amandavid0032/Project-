<?php
$conn = mysqli_connect("localhost", "root", "", "record") or die("Connection failed:" . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateid = $_POST["id"];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phoneno = $_POST['phone'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $imagename = isset($_FILES['imagename']['name']) ? $_FILES['imagename']['name'] : '';
    $imagetmp = isset($_FILES['imagename']['tmp_name']) ? $_FILES['imagename']['tmp_name'] : '';
    $uploads_dir = 'uploads/';
    if (!empty($imagename) && !empty($imagetmp)) {
        move_uploaded_file($imagetmp, $uploads_dir . $imagename);
    } else {
        $imagename = $_POST['existing_image'];
    }
    $sql = $conn->prepare("UPDATE studentrecord SET `f_name`=?, `l_name`=?, `age`=?, `emailId`=?, `phone`=?, `gender`=?, `userimage`=? WHERE id=?");
    $sql->bind_param("sssssssi", $firstname, $lastname, $age, $email, $phoneno, $gender, $imagename, $updateid); 
    $sql->execute();
    if ($sql) {
        echo 1; 
    } else {
        echo 0; 
    }

    mysqli_close($conn);
}
?>
