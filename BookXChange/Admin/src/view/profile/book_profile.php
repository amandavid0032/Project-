<?php
/**
 * This is the page, which call the twig file to show the profile of book.
 *
 * PHP version 8.1.3
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
require '../../../vendor/autoload.php';
require '../../config/db.php';
require '../../include/header.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    echo $book->bookProfile($id);
    // echo "inside book Profile";
}

?>