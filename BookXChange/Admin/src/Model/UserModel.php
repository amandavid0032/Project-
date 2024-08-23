<?php
/**
 * Bookcontroller that controls all the book functionality
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
namespace Book\Bookxchange\Model;

/**
 * User Model that controls all the queries related to User
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
class UserModel
{
    /**
     * Constructor for the User Model.
     * 
     * @param $conn is the object database connection.
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * Function to check if the user exist
     * 
     * @param $uname is the string which holds the userName
     * 
     * @return return true if the username exists else false
     */
    public function checkUserModel(string $uname): bool
    {
        $checkUserStmt = $this->conn->prepare(
            "select id from register where user_name = ?"
        );
        $checkUserStmt->bind_param("s", $uname);
        $checkUserStmt->execute();
        $result = $checkUserStmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to get the password of the specific user
     * 
     * @param $uname is the string which holds the username of the user.
     * 
     * @return returns the hashed password of the user.
     */
    public function getPassModel($uname) : array
    {
        $getPassStmt = $this->conn->prepare(
            "select password from register where user_name = ?"
        );
        $getPassStmt->bind_param("s", $uname);
        $getPassStmt->execute();
        $result = $getPassStmt->get_result();
        $passRst = $result->fetch_assoc();
        return $passRst;
    }


    /**
     * Function to get the Details of the user.
     * 
     * @return returns the data of user.
     */
    public function getUserDataModel() : array
    {
        $bSatus = "blocked";
        $aStatus = "active";
        $user_type = 0;
        $allUserData = $this->conn->prepare(
            "SELECT COUNT(*) as total, (select COUNT(*) FROM register
            where status = ? and user_type = ?) as blocked,
            (select COUNT(*) FROM register where status = ? and
            user_type = ?) as active
            From register
            WHERE user_type = ?"
        );
        $allUserData->bind_param(
            "sisii", $bSatus, $user_type, $aStatus, $user_type, $user_type
        );
        $allUserData->execute();
        $result = $allUserData->get_result();
        $data = $result->fetch_assoc();
        return $data;
    }

    /**
     * Function to get the details of books overall.
     * 
     * @return returns the array of the book data overall.
     */
    public function getBookDataModel() : array
    {
        $bSatus = "blocked";
        $aStatus = "active";

        $allBookData = $this->conn->prepare(
            "SELECT COUNT(*) as total, (select COUNT(*) FROM books
            where status = ?) as blocked, (select COUNT(*) FROM books
            where status = ?) as active
            From books"
        );
        $allBookData->bind_param("ss", $bSatus, $aStatus);
        $allBookData->execute();
        $result = $allBookData->get_result();
        $data = $result->fetch_assoc();
        return $data;
    }


    /**
     * Function to get list of all users.
     * 
     * @return return the list of all the user to the twig file.
     */
    public function getUsersModel() : array
    {
        $user_type = 0;
        $user_data = array();
        $getUserStmt = $this->conn->prepare(
            "select * from register where user_type = ?"
        );
        $getUserStmt->bind_param("i", $user_type);
        $getUserStmt->execute();

        $result = $getUserStmt->get_result();
        $user_data = $result->fetch_all(MYSQLI_ASSOC);

        return $user_data;
    }

    

    /**
     * Function to get the user details for the profile.
     * 
     * @param $id is the unique id whose profile needs to be viewed.
     * 
     * @return return the user details.
     */
    public function getUserDetails(int $id): array
    {
        $userDetailsStmt = $this->conn->prepare(
            "select * from register where id = ?"
        );
        $userDetailsStmt->bind_param("i", $id);
        $userDetailsStmt->execute();
        $userResult = $userDetailsStmt->get_result();
        $data = $userResult->fetch_assoc();

        return $data;
    }

    /**
     * Function to get the userBook Details for the profile pupose.
     * 
     * @param $id is the id of user whose book details need to be viewed.
     * 
     * @return the book details of that particular user.
     */
    public function userBookDetailsModel(int $id) : array
    {
        $userDetailsStmt = $this->conn->prepare(
            "SELECT receiver.user_name as receiver_name, owner.user_name
            as owner_name,b.book_name,r.status, r.reason,
            r.rqst_date, r.issued_date, r.return_date 
            FROM request as r
            INNER JOIN register as receiver ON receiver.id=r.requester_id
            INNER JOIN register as owner ON owner.id=r.owner_id
            INNER JOIN books as b ON b.id = r.book_id
            WHERE r.requester_id = ? order by r.rqst_date"
        );
        $userDetailsStmt->bind_param("i", $id);
        $userDetailsStmt->execute();
        $result = $userDetailsStmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;

    }


    /**
     * Function to delete the user
     * 
     * @param $id is the unique id of the user to delete the data of 
     *            that particular user.
     * 
     * @return returns true after deleting the user.
     */
    public function deleteUserModel(int $id) : bool
    {
        $userDltStmt = $this->conn->prepare(
            "Delete from register where id = ?"
        );
        $userDltStmt->bind_param("i", $id);
        $dltRst = $userDltStmt->execute();
        return true;

    }

    /**
     * Function to block the user
     * 
     * @param $id is the integer value that holds the id of the
     *            user that needs to be blocked.
     * 
     * @return returns true after blocking the user.
     */
    public function blockUserModel(int $id) : bool
    {
        $status = "blocked";
        $userDltStmt = $this->conn->prepare(
            "update register set status = ? where id = ?"
        );
        $userDltStmt->bind_param("si", $status, $id);
        $userDltStmt->execute();
        return true;


    }


    /**
     * Function to show edit the user form.
     * 
     * @param $id is the id of the user whose data needs to be edited.
     * 
     * @return returns the data of the user.
     */
    public function editUserFormModel(int $id) : array
    {
        $editUserStmt = $this->conn->prepare("select * from register where id = ?");
        $editUserStmt->bind_param("i", $id);
        $editUserStmt->execute();
        $result = $editUserStmt->get_result();
        $editArray = $result->fetch_array(MYSQLI_ASSOC);
        return $editArray;
        // echo "<pre>";
        // print_r($editArray);
    }


    /**
     * Function to update the user details.
     * 
     * @param $id       is the id of the user whose data needs to be updated.
     * @param $uName    string value holds the updated name of the user.
     * @param $uMobile  string, holds the phone number of the user.
     * @param $uAddress string, holds the address of the user.
     * @param $uEmail   string, is the string that holds the email 
     *                  Address of the user.
     * @param $uRating  float, holds the rating for that user.
     * 
     * @return nothing to return.
     */
    public function updateUserModel(
        int $id,string $uName,string $uMobile, string $uAddress,
        string $uEmail
    ) : bool {
        $updateStmt = $this->conn->prepare(
            "update register set user_name = ?, mobile_no = ?,
            address = ?, email = ? where id = ?"
        );
        $updateStmt->bind_param(
            "ssssi", $uName, $uMobile, $uAddress, $uEmail, $id
        );
        $updateStmt->execute();
        return true;
    }


    /**
     * Function to unblock the user
     * 
     * @param $id is the integer value that holds the id of the
     *            user which needs to be unblocked.
     * 
     * @return returns true after unblocking the user.
     */
    public function unBlockUserModel(int $id) : bool
    {
        $status = "active";
        $unBlockStmt = $this->conn->prepare(
            "update register set status = ? where id = ?"
        );
        $unBlockStmt->bind_param("si", $status, $id);
        $unBlockStmt->execute();
        return true;
    }

    /**
     * Function to get the welcome message in the login form
     * 
     * @return return a welcome message from the database.
     */
    public function getWelcomeModel() : string
    {
        $wlc = "welcome_text";
        $getWlcStmt = $this->conn->prepare(
            "select value from setting where name = ?"
        );
        $getWlcStmt->bind_param("s", $wlc);
        $getWlcStmt->execute();
        $getWlcResult = $getWlcStmt->get_result();
        $Wlc = $getWlcResult->fetch_array(MYSQLI_ASSOC);
        return $Wlc['value'];
    }
}