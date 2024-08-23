<?php
/**
 * Short description for file
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
namespace Book\Bookxchange\Model;

/**
 * PermissionModel class is a class containing all the methods
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
class PermissionModel
{
    /**
     * Constructor for the permissionController
     *
     * @param $conn is the object for the connection with database
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * Function to get the permission for the User
     * 
     * @return returns array for the user Permission
     */
    public function UPermission() : array
    {
        $userManagerId = 1;
        $UPermisionStmt = $this->conn->prepare(
            "select * from permission where id = ?"
        );
        $UPermisionStmt->bind_param("i", $userManagerId);
        $UPermisionStmt->execute();
        $result = $UPermisionStmt->get_result();
        $userPermission = $result->fetch_assoc();

        return $userPermission;
    }

    /**
     * Function to get the permission for the User
     * 
     * @return returns array for the user Permission
     */
    public function BPermission() : array
    {
        $bookManagerId = 2;
        $BPermisionStmt = $this->conn->prepare(
            "select * from permission where id = ?"
        );
        $BPermisionStmt->bind_param("i", $bookManagerId);
        $BPermisionStmt->execute();
        $result = $BPermisionStmt->get_result();
        $bookPermission = $result->fetch_assoc();

        return $bookPermission;
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
     * @return Nothing true after the setting permission for user
     */
    public function setPermissionUserModel(
        string $user_table,string $book_table,string $request,string $setting
    ) : bool {

        $userPermissionId = 1;
        $userPermissionValue = 0;
        $value = 1;

        $setZeroStmt = $this->conn->prepare(
            "update permission set user_table = ?, book_table = ?,
            request = ?,  settings = ? where id = ?"
        );
        $setZeroStmt->bind_param(
            "iiiii", $userPermissionValue, $userPermissionValue,
            $userPermissionValue, $userPermissionValue, $userPermissionId
        );
        $setZeroStmt->execute();
        if ($user_table == 'yes') {
            $userTableStmt = $this->conn->prepare(
                "update permission set user_table = ? where id = ?"
            );
            $userTableStmt->bind_param("ii", $value, $userPermissionId);
            $userTableStmt->execute();
        }
        if ($book_table == 'yes') {
            $bookTableStmt = $this->conn->prepare(
                "update permission set book_table = ? where id = ?"
            );
            $bookTableStmt->bind_param("ii", $value, $userPermissionId);
            $bookTableStmt->execute();
        }
        if ($request == 'yes') {
            $requestStmt = $this->conn->prepare(
                "update permission set request = ? where id = ?"
            );
            $requestStmt->bind_param("ii", $value, $userPermissionId);
            $requestStmt->execute();
        }
        if ($setting == 'yes') {
            $settingStmt = $this->conn->prepare(
                "update permission set settings = ? where id = ?"
            );
            $settingStmt->bind_param("ii", $value, $userPermissionId);
            $settingStmt->execute();
        }
        return true;

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
     * @return Nothing true after the setting permission for BOok manager
     */
    public function setPermissionBookModel(
        string $user_table,string $book_table,string $request,string $setting
    ) : bool {

        $bookPermissionId = 2;
        $bookPermissionValue = 0;
        $value = 1;

        $setZeroStmt = $this->conn->prepare(
            "update permission set user_table = ?, book_table = ?,
            request = ?,  settings = ? where id = ?"
        );
        $setZeroStmt->bind_param(
            "iiiii", $bookPermissionValue, $bookPermissionValue,
            $bookPermissionValue, $bookPermissionValue, $bookPermissionId
        );
        $setZeroStmt->execute();
        if ($user_table == 'yes') {
            $userTableStmt = $this->conn->prepare(
                "update permission set user_table = ? where id = ?"
            );
            $userTableStmt->bind_param("ii", $value, $bookPermissionId);
            $userTableStmt->execute();
        }
        if ($book_table == 'yes') {
            $bookTableStmt = $this->conn->prepare(
                "update permission set book_table = ? where id = ?"
            );
            $bookTableStmt->bind_param("ii", $value, $bookPermissionId);
            $bookTableStmt->execute();
        }
        if ($request == 'yes') {
            $requestStmt = $this->conn->prepare(
                "update permission set request = ? where id = ?"
            );
            $requestStmt->bind_param("ii", $value, $bookPermissionId);
            $requestStmt->execute();
        }
        if ($setting == 'yes') {
            $settingStmt = $this->conn->prepare(
                "update permission set settings = ? where id = ?"
            );
            $settingStmt->bind_param("ii", $value, $bookPermissionId);
            $settingStmt->execute();
        }
        return true;
    }

    /**
     * Function to get access permission for userManager
     * 
     * @return returns array of permission for the user.
     */
    public function getAccessUserModel() : array
    {

        $user_type = 2;
        $userAccessStmt = $this->conn->prepare(
            "select * from permission where user_type = ?"
        );
        $userAccessStmt->bind_param("i", $user_type);
        $userAccessStmt->execute();
        $userAccessRst = $userAccessStmt->get_result();
        $userAccessPermissions = $userAccessRst->fetch_array(MYSQLI_ASSOC);
        return $userAccessPermissions;
        // echo "<pre>";
        // print_r($userAccessPermissions);


    }

    /**
     * Function to get access permission for userManager
     * 
     * @return returns array of permission for the Book.
     */
    public function getAccessBookModel() : array
    {

        $book_type = 3;
        $bookAccessStmt = $this->conn->prepare(
            "select * from permission where user_type = ?"
        );
        $bookAccessStmt->bind_param("i", $book_type);
        $bookAccessStmt->execute();
        $bookAccessRst = $bookAccessStmt->get_result();
        $bookAccessPermissions = $bookAccessRst->fetch_array(MYSQLI_ASSOC);
        return $bookAccessPermissions;

    }
}
