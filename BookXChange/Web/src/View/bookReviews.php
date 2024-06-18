<?php
/**
 * Dashboard page.
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
use Bookxchange\Bookxchange\Controller\Book;
session_start();
$base = new Baseurl();
$baseurl = $base->getBaseurl();
$book = new Book($baseurl); 
$home = new Home($baseurl);
$session = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

echo $home->getHeader($session);
if (isset($_SESSION['msg'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show every-msg" role="alert">
 '.$_SESSION['msg'].'
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
    unset($_SESSION['msg']);
}
if (isset($_GET['bookid'])) {
  $bookId = $_GET['bookid'];
}
if (isset($_GET['bookid'])) {
    $bookId = $_GET['bookid'];
}
echo $book->getReviews($bookId);    
echo $home->getFooter();
?>
