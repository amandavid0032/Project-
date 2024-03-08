<?php
require_once '../vendor/autoload.php';
require_once '../model/main-db.php';
$loader = new Twig\Loader\FilesystemLoader('../Template');
$twig = new Twig\Environment($loader);
echo $twig->render('login.twig');
$database = new Database();
$database->noLoginSession();
$rows = '*';
$limit = 10;
$page = 1;
$table = 'user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 
    $where = array('email' => $email, 'password' => $password);
    $whereClause = '';
    foreach ($where as $key => $value) {
        $whereClause .= "$key = '$value' AND ";
    }
    $whereClause = rtrim($whereClause, 'AND ');
    $result = $database->select($table, $rows, $limit, $page, $whereClause);
    if (mysqli_num_rows($result) > 0) {
        $user = $result->fetch_assoc(); 
        session_start();
        $_SESSION['email'] = $user['email'];
        if ($user['type'] == 1) {
             $_SESSION = [
                'email' => $user['email'],
                'type' => $user['type'],
                'uid' =>  $user['uid']
            ];
            header("Location:admin/admin-view.php");
            exit();
        } elseif ($user['type'] == 0) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['uid'] = $user['uid'];
            header("Location:user/user-view.php");
            exit();
        }
    } else {
        $message = 'Invalid email or password.';
        $color = 'danger';
        header("Location: login.php?message=" . urlencode($message) . "&color=$color");
        echo $password;
        exit();
    }
}
?>
