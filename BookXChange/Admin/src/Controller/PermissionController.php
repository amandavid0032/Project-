<?php
/**
 * Permission Controller that controlls all the functions
 * regarding the permission
 *
 * Long description for file (if any)...
 *
 * PHP version 7.4.30
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Original Author <author@example.com>
 * @author     Another Author <another@example.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
namespace Book\Bookxchange\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * PermissionController class is a class containing all the methods
 * regarding the permission
 *
 * PHP version 8.1.3
 *
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
class PermissionController
{
    private $_loader;
    private $_twig;

    /**
     * Constructor for the permissionController
     *
     * @param $permission_m is the object for the bookModel
     */
    public function __construct($permission_m)
    {

        $this->loader = new FilesystemLoader(__DIR__ . '/../view/templates');
        $this->twig = new Environment($this->loader);
        $this->permission_m = $permission_m;
    }

    /**
     * Function to show all the permission
     *
     * @return returns all the permission setting to the twig file
     */
    public function permission()
    {
        $UPermission = $this->permission_m->UPermission();
        $BPermission = $this->permission_m->BPermission();
        if ($UPermission && $BPermission) {
            return $this->twig->render(
                'permission.html.twig', [
                    "uPermission" => $UPermission,
                    "bPermission" => $BPermission
                ]
            );
        }

    }

    /**
     * Function to set permission for the userManager.
     *
     * @param $user_table is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $book_table is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $request    is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $setting    is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     *
     * @return Nothing to return
     */
    public function setPermissionUser(
        string $user_table, string $book_table,
        string $request, string $setting
    ) {
        $userRst = $this->permission_m->setPermissionUserModel(
            $user_table, $book_table, $request, $setting
        );
        if ($userRst) {
            $_SESSION['success'] = "User Manager permission successfully assigned";
            header('location:permission.php');
            exit;
        }
    }

    /**
     * Function to set permission for the BookManager.
     *
     * @param $user_table is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $book_table is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $request    is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     * @param $setting    is a string value, hold 'yes'
     *                    if allowed to get access else 'no'.
     *
     * @return Nothing to return
     */
    public function setPermissionBook(
        string $user_table, string $book_table, string $request, string $setting
    ) {
        $bookRst = $this->permission_m->setPermissionBookModel(
            $user_table, $book_table, $request, $setting
        );
        if ($bookRst) {
            $_SESSION['success'] = "Book Manager permission successfully assigned";
            header('location:permission.php');
            exit;
        }
    }

    /**
     * Function to get access permission for userManager
     * 
     * @return returns permissions for userManger
     */
    public function getAccessUser() : array
    {
        $user = $this->permission_m->getAccessUserModel();
        return $user;
    }

    /**
     * Function to get access permission for bookManager
     * 
     * @return returns permissions for userManger
     */
    public function getAccessBook() : array
    {
        $book = $this->permission_m->getAccessBookModel();
        return $book;
    }
}
