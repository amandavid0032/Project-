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
require '../Includer/common.php';
if (isset($_GET['bookid'])) {
    $bookId = $_GET['bookid'];
}
echo $book->getPersonalViewBook($bookId);
echo $home->getFooter();
?>