<?php
$hostname = 'localhost';
$user = "root";
$pass = "";
$dbname = "tinder-project";

$conn = mysqli_connect($hostname, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Contact us
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $currentDate = date('Y-m-d H:i:s');
    $insert = "INSERT INTO contact VALUES (null, '$name', '$email', '$message','$currentDate')";

    if (mysqli_query($conn, $insert)) {
        $message =  'Your Query is successfully Added ';
        $color = 'success';
        header("location:index.php?message=" . urlencode($message) . "&color=$color");
    } else {
        echo "Something is wrong in Database: ";
    }
}
// singup code
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $currentDate = date('Y-m-d H:i:s');
    $role = $_POST['role'];
    $insert = "INSERT INTO user VALUES (null ,'$name','$email','$pass',0,'$currentDate',0)";
    if (mysqli_query($conn, $insert)) {
        $message =  'Your Account is  created successfully';
        $color = 'success';
        header("location:signup.php?message=" . urlencode($message) . "&color=$color");
    } else {
        echo "Something is wrong: ";
    }
}

// question
if (isset($_POST['question'])) {
    session_start();
    $id = $_SESSION['uid'];
    // houseimage 
    $image_name = $_FILES['imagename']['name'];
    $tmp_name = $_FILES['imagename']['tmp_name'];
    move_uploaded_file($tmp_name, './../image/' . $image_name);
    // room image
    $roomimage = $_FILES['image']['name'];
    $tmp_name2 = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp_name2, './../image/' . $roomimage);

    $property = $_POST['property-type'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $laundry = $_POST['value'];
    $parking = $_POST['parking-type'];
    $ac = $_POST['ac-type'];
    $heating = $_POST['heating-available'];
    $amenities = isset($_POST['amenities']) ? $_POST['amenities'] : array();
    if (isset($amenities) && is_array($amenities)) {
        $selectedAmenities = implode(', ', $amenities);
    }
    $insert = "INSERT INTO question VALUES (null, '$id', '$image_name', '$roomimage', '$property', $bedrooms, $bathrooms, '$address', '$description', '$laundry', '$parking', '$ac', '$heating', '$selectedAmenities')";
    if (mysqli_query($conn, $insert)) {
        $update = "UPDATE user SET isfiled= 1 WHERE `uid`=$id";
        if (mysqli_query($conn, $update)) {
            $message = ' Property Information Filled successful';
            $color = 'success';
            header("location: user-page.php?message=" . urlencode($message) . "&color=$color");
            exit;
        } else {
            echo "Error inserting question: ";
        }
    }
}

// login checking code
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $user['email'];
        if ($user['type'] == 1) {
            session_start();
            $_SESSION = [
                'email' => $user['email'],
                'type' => $user['type'],
                'uid' =>  $user['uid']
            ];
            header("Location:admin/admin-view.php");
        } elseif ($user['type'] == 0) {
            session_start();
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['uid'] = $user['uid'];
            header("Location:user/user-page.php");
        }
    } else {
        $message = 'Invalid email or password.';
        $color = 'danger';
        header("location: signin.php?message=" . urlencode($message) . "&color=$color");
        exit();
    }
}


function loginSession()
{
    session_start();
    if (!isset($_SESSION['email'])) {
        header("location: ./../index.php");
        exit;
    }
}

function noLoginSession()
{
    session_start();
    if (isset($_SESSION['email'])) {
        if ($_SESSION['type'] == 1) {
            header("location: ./admin/admin-view.php");
        } elseif ($_SESSION['type'] == 0) {
            header("location: ./user/user-page.php");
        }
    }
}

// Accpect  and Reject code
if (isset($_POST['accept'])) {
    $uid = $_POST['uid'];
    $id = $_POST['accept'];
    $insert = "INSERT INTO accpect_reject VALUES (null, '$uid','$id', 1)";
    if (mysqli_query($conn, $insert)) {
        $message = 'Accept successfully';
        $color = 'success';
        header("Location: user-page.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        echo "Something went wrong: " . $insert . "<br>" . mysqli_error($conn);
    }
} elseif (isset($_POST['reject'])) {
    $uid = $_POST['uid'];
    $id = $_POST['reject'];
    $insert = "INSERT INTO accpect_reject VALUES (null, '$uid','$id', 0)";
    if (mysqli_query($conn, $insert)) {
        $message = 'Reject successfully';
        $color = 'danger';
        header("Location: user-page.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        echo "Something went wrong: ";
    }
}
