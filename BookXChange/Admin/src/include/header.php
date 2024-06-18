<?php
/**
 * Header file that is included in most of the pages.
 * it includes the calling of the models and the constructors. 
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
$base_url = "http://localhost/BookXChange";

use Book\Bookxchange\Model\UserModel;
use Book\Bookxchange\Model\BookModel;
use Book\Bookxchange\Model\RequestModel;
use Book\Bookxchange\Model\SettingModel;
use Book\Bookxchange\Model\PermissionModel;

$user_m = new UserModel($conn);
$book_m = new BookModel($conn);
$reqst_m = new RequestModel($conn);
$setting_m = new SettingModel($conn);
$permission_m = new PermissionModel($conn);

$user = new \Book\Bookxchange\Controller\UserController($user_m);
$book = new \Book\Bookxchange\Controller\BookController($book_m);
$reqst = new \Book\Bookxchange\Controller\RequestController($reqst_m);
$setting = new \Book\Bookxchange\Controller\SettingController($setting_m);
$permission = new \Book\Bookxchange\Controller\PermissionController($permission_m);

//calling setTitle function in the settingController to set the title of the page
$title = $setting->getTitle();
//Calling setLogo function in the settingController to set the logo
$logo = $setting->getLogo();

//get access for user and book manager
$userManager = $permission->getAccessUser();
$bookManager = $permission->getAccessBook();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../style/style.css">
    <title><?php echo $title; ?></title>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <?php  if (!isset($_SESSION['login'])) { ?>
            <a class="navbar-brand" href=""><img src="
                <?php $base_url ?>/bookxchange/Admin/src/img/<?php echo $logo; ?>
            "width="50px" alt="Logo Image"></a>

            <?php } if (isset($_SESSION['login'])) { ?>

            <a class="navbar-brand" href="
                <?php $base_url ?>/bookxchange/Admin/src/view/welcome.php">
                <img src="<?php $base_url ?>/bookxchange/src/img/<?php
                echo $logo; ?>" width="50px" alt="Logo Image"></a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                       <!-- for user Manager -->
                       <?php if ($_SESSION['loggedIn'] == 'userManager') { 
                            if ($userManager['user_table'] == 1 ) {
                                ?>
                                <a class="nav-link" id="user" href="
                                <?php $base_url ?>/bookxchange/Admin/src/view/user_list.php"
                                >Users</a>
                        <!-- for book Manager -->
                            <?php }
                       } if ($_SESSION['loggedIn'] == 'bookManager') { 
                           if ($bookManager['user_table'] == 1 ) {
                                ?>
                                <a class="nav-link" id="user" href="
                                <?php $base_url ?>/bookxchange/Admin/src/view/user_list.php"
                                >Users</a>
                        <!-- for superadmin -->
                           <?php }
                       } if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'superadmin' ) { ?>
                       
                        <a class="nav-link" id="user" href="
                            <?php $base_url ?>/bookxchange/Admin/src/view/user_list.php"
                            >Users</a>
                       <?php } ?>
                    </li>
                    <li class="nav-item">
                    <?php if ($_SESSION['loggedIn'] == 'userManager') { 
                        if ($userManager['book_table'] == 1 ) {
                            ?>
                        <a class="nav-link" id="book" href="
                            <?php $base_url ?>/bookxchange/Admin/src/view/book_list.php"
                            >Books</a>
                        <?php }
                    } ?>
                        <?php if ($_SESSION['loggedIn'] == 'bookManager') { 
                            if ($bookManager['book_table'] == 1 ) {
                                ?>
                        <a class="nav-link" id="book" href="
                                <?php $base_url ?>/bookxchange/Admin/src/view/book_list.php"
                                >Books</a>
                            <?php }
                        } ?>
                        

                <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'superadmin' ) { ?>
                        <a class="nav-link" id="book"  href="
                        <?php $base_url ?>/bookxchange/Admin/src/view/book_list.php"
                        >Books</a>
                <?php } ?>
                    </li>
                    <li class="nav-item">
                    <?php if ($_SESSION['loggedIn'] == 'userManager') { 
                        if ($userManager['request'] == 1 ) {
                            ?>
                        <a class="nav-link" id="rqst" href="
                            <?php $base_url ?>/bookxchange/Admin/src/view/request.php">Request</a>
                        <?php }
                    } ?>
                    <?php if ($_SESSION['loggedIn'] == 'bookManager') { 
                        if ($bookManager['request'] == 1 ) {
                            ?>
                        <a class="nav-link" id="rqst" href="
                            <?php $base_url ?>/bookxchange/Admin/src/view/request.php">Request</a>
                        <?php }
                    } ?>

                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'superadmin' ) { ?>

                        <a class="nav-link" id="rqst" href="
                            <?php $base_url ?>/bookxchange/Admin/src/view/request.php"
                            >Request</a>

                        <?php } ?>
                    </li>
                    <li class="nav-item">
                    <?php if ($_SESSION['loggedIn'] == 'userManager') { 
                        if ($userManager['settings'] == 1 ) {
                            ?>
                        <a id="sett" href="<?php $base_url ?>/bookxchange/Admin/src/settings/setting.php"
                            class="nav-link">Settings</a>
                        <?php }
                    } ?>
                    <?php if ($_SESSION['loggedIn'] == 'bookManager') { 
                        if ($bookManager['settings'] == 1 ) {
                            ?>
                        <a id="sett" href="<?php $base_url ?>/bookxchange/Admin/src/settings/setting.php"
                            class="nav-link">Settings</a>
                        <?php }
                    } ?>
                        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'superadmin' ) { ?>

                            <a id="sett" href="<?php $base_url ?>/bookxchange/Admin/src/settings/setting.php"
                            class="nav-link">Settings</a>

                        <?php } ?>

                    </li>
                    <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']== 'superadmin') { ?>
                    <li class="nav-item">
                        <a id="perm" href="<?php $base_url ?>/bookxchange/Admin/src/settings/permission.php"
                            class="nav-link">Permissions</a>
                    </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']== 'superadmin') { ?>
                    <li class="nav-item">
                        <a id="lang" href="<?php $base_url ?>/bookxchange/Admin/src/view/language.php"
                            class="nav-link">Languages</a>
                    </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']== 'superadmin') { ?>
                    <li class="nav-item">
                        <a id="genre" href="<?php $base_url ?>/bookxchange/Admin/src/view/genre.php"
                            class="nav-link">Genre</a>
                    </li>
                    <?php } ?>


                </ul>

            </div>
                <?php if (isset($_SESSION['loggedIn'])) {
                    echo '<p class="text-light mx-3">Welcome :
                    '.$_SESSION['loggedIn'].'</p>';
                } ?>


            <a href="<?php $base_url ?>/bookxchange/Admin/src/view/logout.php"
             class="btn btn-primary">LogOut</a>

            <?php } ?>
        </div>
    </nav>


    <script src="../../bootstrap/js/bootstrap.js"></script>
    <script src="../js/jquery-3.6.0.js"></script>
    <!-- <script src="../js/script.js"></script> -->
    <script>
        $("a").click(function(){
        $(a).removeClass("active");
        $(this).addClass("active");
    });
    console.log("dslk");
    </script>

<!-- </body>

</html> -->