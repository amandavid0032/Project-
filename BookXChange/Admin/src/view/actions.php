<?php
/**
 * After clicking on any actions like delete, edit or block contorller
 * comes here and choose action according the requirement.
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


use Book\Bookxchange\Model\BookModel;
use Book\Bookxchange\Model\UserModel;
use Book\Bookxchange\Model\SettingModel;

$user_m = new UserModel($conn);
$book_m = new BookModel($conn);
$setting_m = new SettingModel($conn);

$user = new \Book\Bookxchange\Controller\UserController($user_m);
$book = new \Book\Bookxchange\Controller\BookController($book_m);
$setting = new \Book\Bookxchange\Controller\SettingController($setting_m);


if (isset($_POST['update_user'])) {
    $id = intval($_GET['id']);
    $uName = $_POST['u_name'];
    $uMobile = $_POST['u_mobile'];
    $uAddress = $_POST['u_address'];
    $uEmail = $_POST['u_email'];
    // $uRating = floatval($_POST['u_rating']);

    $user->updateUser($id, $uName, $uMobile, $uAddress, $uEmail);
}

if (isset($_POST['login'])) {
    $uname = $_POST['uname'];
    $upass = $_POST['upass'];
    $user->login($uname, $upass);
}

if (isset($_POST['addLang'])) {
    $language = $_POST['lang'];
    echo $setting->addLang($language);
}
if (isset($_POST['addGenre'])) {
    $genre = $_POST['genre'];
    echo $setting->addGenre($genre);
}

if (isset($_GET['id']) && isset($_GET['action'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "deleteUser") {
        $user->deleteUser($id);
    } elseif ($action == "blockUser") {
        $user->blockUser($id);
    } elseif ($action == "unBlockUser") {
        $user->unBlockUser($id);
    } elseif ($action == "block_book") {
        $book->blockBook($id);       
    } elseif ($action == "unblockBook") {
        $book->unBlockBook($id);
    } elseif ($action == "delete_book") {
        $book->deleteBook($id);
    }

}

?>
