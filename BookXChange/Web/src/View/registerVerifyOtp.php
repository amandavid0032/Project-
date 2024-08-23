<?php
/**
 * Register otp.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require '../../vendor/autoload.php';

use Bookxchange\Bookxchange\Config\Baseurl;
use Bookxchange\Bookxchange\Controller\Home;
use Bookxchange\Bookxchange\Controller\User;

session_start();
$base = new Baseurl();
$baseurl = $base->getBaseurl();
$home = new Home($baseurl);
$user = new User($baseurl);
if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header('location:dashboard.php');
}
if (isset($_GET['formcode']) 
    && isset($_SESSION['verify_otp_form'])
    && ($_GET['formcode'] == $_SESSION['verify_otp_form'])
) {
    $session = null;
    echo $home->getHeader($session);
    if (isset($_SESSION['msg'])) {
        echo '<div class="alert alert-danger
         alert-dismissible fade show every-msg" role="alert">
 '.$_SESSION['msg'].'
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
        unset($_SESSION['msg']);
    }
    echo $user->registerVerifyOtp();
} else {
    header('location:signin.php');
}
