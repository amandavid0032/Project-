<?php
/**
 * Apply changes page, this page is called when we applying changes in setting page.
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



if (isset($_POST['apply'])) {
    
    $title = $_POST['site_title'];
    $logo = $_FILES['logo'];
    $mail = $_POST['mail_from'];
    $welcome = $_POST['welcome_text'];
    // echo $logo." ".$logo." ".$mail." ".$welcome;

    if ($logo['name'] != "") {
        $setting->updateLogo($logo);
    }

    $setting->applyChange($title, $mail, $welcome);

}


?>
