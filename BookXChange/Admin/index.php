<?php
/**
 * This is the landing page of the BookXchange application.
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

require 'vendor/autoload.php';
    
require 'src/config/db.php';
require 'src/include/header.php';

if (isset($_SESSION['wrong']) && $_SESSION['wrong'] == "false") {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Sorry </strong> Invalid credentials.
    <button type="button" class="btn-close" data-bs-dismiss="alert"
	aria-label="Close"></button>
      </div>';  
      unset($_SESSION['wrong']);
}

if (!isset($_SESSION['login']) ) {
    // include 'src/include/loginForm.php';
    echo $user->logInForm();

}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">


  </head>
  <body>
    

    <script src="bootstrap/js/boostrap.js"></script>
  </body>
</html>