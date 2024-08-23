<?php
/**
 * Index page that controls login.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

require 'vendor/autoload.php';

use Bookxchange\Bookxchange\Config\Baseurl; 
use Bookxchange\Bookxchange\Controller\Home;
$base = new Baseurl();
$baseurl = $base->getBaseurl(); 
$home = new Home($baseurl);
session_start();
if (isset($_SESSION['user_id'])
    && isset($_SESSION['token'])
) {
    header('location:src/View/dashboard.php');
} else {
    echo $home->getHome();
    if (isset($_SESSION['msg'])) {
        echo '<div class="alert alert-danger
         alert-dismissible fade show" role="alert">
        '.$_SESSION['msg'].'
           <button type="button" class="close" data-dismiss="alert"
            aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>';
        unset($_SESSION['msg']);
    }
}
?>
