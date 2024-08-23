<?php
/**
 * This page is called when the user wants to set 
 * permission for the userManager and bookManager.
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
require '../../vendor/autoload.php';
require '../config/db.php';
require '../include/header.php';


if (isset($_POST['setPermissionUser'])) {

    if (!empty($_POST['permission']) ) {
        // echo "<pre>";
        // print_r($_POST['permission']);
        $user_table = (in_array('user', $_POST['permission'])) ? "yes" : "no";
        $book_table = (in_array('book', $_POST['permission'])) ? "yes" : "no";
        $request = (in_array('rqst', $_POST['permission'])) ? "yes" : "no";
        $sett = (in_array('setting', $_POST['permission'])) ? "yes" : "no";
        // echo $user_table." ".$book_table." ".$request." ".$setting;
        $permission->setPermissionUser($user_table, $book_table, $request, $sett);
        

    }
}
if (isset($_POST['setPermissionBook'])) {

    if (!empty($_POST['permission']) ) {
        // echo "<pre>";
        // print_r($_POST['permission']);
        $user_table = (in_array('user', $_POST['permission'])) ? "yes" : "no";
        $book_table = (in_array('book', $_POST['permission'])) ? "yes" : "no";
        $request = (in_array('rqst', $_POST['permission'])) ? "yes" : "no";
        $sett = (in_array('setting', $_POST['permission'])) ? "yes" : "no";
        // echo $user_table." ".$book_table." ".$request." ".$setting;
        // echo "<pre>";
        // print_r($user_table);
        $permission->setPermissionBook($user_table, $book_table, $request, $sett);
        

    }
}

?>
