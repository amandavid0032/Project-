<?php
/**
 * Book list page, this page is called to show all the list of the book.
 *
 * PHP version 7.4.30
 *
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
require '../../vendor/autoload.php';
require '../config/db.php';
require '../include/header.php';


if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success </strong>'.$_SESSION['success'].'.
    <button type="button" class="btn-close" data-bs-dismiss="alert"
    aria-label="Close"></button>
    </div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['fail'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Failed </strong>'.$_SESSION['fail'].'.
    <button type="button" class="btn-close" data-bs-dismiss="alert"
    aria-label="Close"></button>
    </div>';
    unset($_SESSION['fail']);
}

$bookListHtml = $book->getBooks();

?>

    <script src="../js/jquery-3.6.0.js"></script>
    <script src="../js/bookAjax.js"></script>

    <link rel="stylesheet" type="text/css" href="../DataTables/datatables.min.css"/>
    <script type="text/javascript" src="../DataTables/datatables.min.js"></script>

    <div id="bookListDiv"><?php echo $bookListHtml;?></div>
    
<?php require '../include/footer.php' ?>
