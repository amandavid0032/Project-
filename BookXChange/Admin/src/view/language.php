<?php
/**
 * Language page, that handle the addition, updation and delettion of laguages.
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
if (isset($_GET['added']) && $_GET['added'] == 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Language </strong> added successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"
	aria-label="Close"></button>
      </div>';  
      unset($_SESSION['wrong']);
}
echo $setting->language();
