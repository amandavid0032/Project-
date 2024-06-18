<?php
/**
 * Sign In page.
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
$home = new Home($baseurl);
$book = new Book($baseurl);

$session = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // search book
    if (isset($_GET['findBook'])) {
        $bookName = isset($_GET['book_title']) ? $_GET['book_title'] : null;
        $bookCat = isset($_GET['book_cat']) ? $_GET['book_cat'] : null;
        $bookAuthor = isset($_GET['book_author']) ? $_GET['book_author'] : null;
        echo $book->getSearchBook($bookName, $bookCat, $bookAuthor);
    }
}
echo $home->getFooter();

?>