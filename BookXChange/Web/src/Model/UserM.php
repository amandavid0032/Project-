<?php
/**
 * User page that controls User.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

namespace Bookxchange\Bookxchange\Model;

use Bookxchange\Bookxchange\Config\DbConnection;

/**
 * User class that controls Users.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class UserM
{
    private $_conn;

    /**
     * Constructor for User.
     */
    public function __construct()
    {
        $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }

    /**
     * Function isEmailExit check email.
     *
     * @param $email email.
     *
     * @return bool true or false.
     */
    public function isEmailExit(string $email): bool
    {
        $sql = "SELECT id FROM  `register` WHERE `email`=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function isPhoneExit check phone
     *
     * @param $phone phone number
     *
     * @return bool true or false
     */
    public function isPhoneExit(string $phone): bool
    {
        $sql = "SELECT id FROM  `register` WHERE mobile_no=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function getLogin get login
     *
     * @param $phoneOrEmail    phone number or email
     * @param $loginFromNumber is true if number and false if email
     *
     * @return array all details
     */
    public function getLogin(string $phoneOrEmail, bool $loginFromNumber): array
    {
        if ($loginFromNumber === true) {
            $sql = "SELECT * FROM `register` WHERE mobile_no=?";
        } else {
            $sql = "SELECT * FROM `register` WHERE email=?";
        }
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $phoneOrEmail);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) {
            exit('No row selected');
        }
        $res = $arr->fetch_assoc();
        $stmt->close();
        return $res;
    }

     /**
      * Function getRegister give register
      *
      * @param $userImage   image for user.
      * @param $userName    is user name.
      * @param $userMobile  is user mobile.
      * @param $userAddress is user address.
      * @param $userEmail   is user email.
      * @param $userPass    is password.
      *
      * @return bool true or false.
      */
    public function updateRegister(
        $userImage,
        $userName,
        $userMobile,
        $userAddress,
        $userEmail,
        $userPass
    ): bool {
        $sql = "UPDATE `register` SET image = ?,
        user_name=?,mobile_no=?,address=?,password=?
         WHERE email = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "ssssss",
            $userImage,
            $userName,
            $userMobile,
            $userAddress,
            $userPass,
            $userEmail,
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function updateToken update token
     *
     * @param $userId user id
     * @param $token  token for user
     *
     * @return bool true or false
     */
    public function updateToken(string $userId, $token): bool
    {
        $sql = "UPDATE `register` SET token=? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $token, $userId);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }

    /**
     * Function updatePassword
     *
     * @param $pass   password.
     * @param $mobile mobile number.
     *
     * @return bool true or false
     */
    public function updatePassword(string $pass, string $mobile): bool
    {
        $sql = "UPDATE `register` SET password=? WHERE mobile_no = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $pass, $mobile);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function userProfile give details of user.
     *
     * @param $userId user id.
     *
     * @return array list of detail of user.
     */
    public function userProfile(int $userId): array
    {
        $sql = "SELECT * FROM `register` WHERE id=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            exit('no row selected');
        }
        $arr = $result->fetch_assoc();
        return $arr;
    }

    /**
     * Function updateProfile
     *
     * @param $newUserImage   user image
     * @param $newUserName    name of user
     * @param $newUserNumber  number of user.
     * @param $newUserAddress address of user.
     * @param $newUserEmail   email of user.
     * @param $userId         is id of user.
     *
     * @return bool true or false.
     */
    public function updateProfile(
        string $newUserImage,
        string $newUserName,
        string $newUserNumber,
        string $newUserAddress,
        string $newUserEmail,
        int $userId
    ) {
        $sql = "UPDATE `register` SET image=?, user_name=?, mobile_no=?, 
        address=?,email=? 
        WHERE id=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "sssssi",
            $newUserImage,
            $newUserName,
            $newUserNumber,
            $newUserAddress,
            $newUserEmail,
            $userId
        );
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }
    /**
     * Function getrating to get previous rating.
     *
     * @param $requesterId is requester id.
     *
     * @return array is array of previous rating and rater .
     */
    public function getUserRating(int $requesterId): array
    {
        $sql = "SELECT rating, rater FROM `register` WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $requesterId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr =$res->fetch_assoc();
        $stmt->close();
        return $arr;
    }
    /**
     * Function userRating
     *
     * @param $rating      rating of user.
     * @param $requesterId user id.
     *
     * @return void nothing.
     */
    public function updateUserRating(float $rating, int $requesterId): void
    {
        $sql = "UPDATE `register` SET rating = ? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("dii", $rating, $requesterId);
        $stmt->execute();
    }

    /**
     * Function getOldPass
     *
     * @param $userId is user id
     *
     * @return static returning old pass.
     */
    public function getOldPass(int $userId)
    {
        $sql = "SELECT `password` FROM `register` WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        return $arr;
    }

    /**
     * Function updateNewPass
     *
     * @param $userId   is user id.
     * @param $hashPass is hass password.
     *
     * @return bool trur or false.
     */
    public function updateNewPass(int $userId, string $hashPass)
    {
        $sql = "UPDATE `register` SET `password` = ? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("si", $hashPass, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($stmt->affected_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }
    /**
     * Function insertRegisterOtp
     *
     * @param $id           is recent id
     * @param $email        is email
     * @param $sixDigitCode is otp code.
     *
     * @return int recent insert id  or 0.
     */
    public function insertRegisterOtp(
        int $id,
        string $email,
        string $sixDigitCode
    ): int {
        if ($id == 0) {
            $userImage = '';
            $userName = '';
            $userMobile = '';
            $userAddress = '';
            $userPass = '';
            $token = '';
            $usertype = 0;
            $status = 'active';
            $sql = "INSERT INTO `register` (image,user_name,mobile_no,address,
        email,password,status,token,user_type,otp) values(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bind_param(
                "ssssssssss",
                $userImage,
                $userName,
                $userMobile,
                $userAddress,
                $email,
                $userPass,
                $status,
                $token,
                $usertype,
                $sixDigitCode
            );
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $res = $stmt->insert_id;
            } else {
                $res = 0;
            }
        } else {
            $sql = "UPDATE `register` SET otp = ? WHERE id = ?";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bind_param("si", $sixDigitCode, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $res = $id;
            } else {
                $res = 0;
            }
        }

        $stmt->close();
        return $res;
    }

    /**
     * Function getRegisterOtp
     *
     * @param $email is email
     *
     * @return array otp
     */
    public function getRegisterOtp(string $email): array
    {
        $sql = "SELECT otp FROM register WHERE email = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $arr = $stmt->get_result();
        $res = $arr->fetch_assoc();
        return $res;
    }

    /**
     * Function getRegister give register
     *
     * @param $userImage   image for user.
     * @param $userName    is user name.
     * @param $userMobile  is user mobile.
     * @param $userAddress is user address.
     * @param $userEmail   is user email.
     * @param $userPass    is password.
     *
     * @return int insert id.
     */
    public function getRegister1(
        $userImage,
        $userName,
        $userMobile,
        $userAddress,
        $userEmail,
        $userPass
    ): int {
        $token = '';
        $usertype = 0;
        $status = 'active';
        $sql = "INSERT INTO `register` (image,user_name,mobile_no,address,
        email,password,status,token,user_type) values(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "sssssssss",
            $userImage,
            $userName,
            $userMobile,
            $userAddress,
            $userEmail,
            $userPass,
            $status,
            $token,
            $usertype
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = $this->_conn->insert_id;
        } else {
            $res = 0;
        }
        $stmt->close();
        return $res;
    }
      /**
       * Function getUserDetailByEmail
       *
       * @param $email is emailadress
       *
       * @return array is array list.
       */
    public function getUserDetailByEmail(string $email): array
    {
        $sql = "SELECT *
         FROM `register` 
         WHERE email=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $arr = [];
        }
        $arr = $result->fetch_assoc();
        return $arr;
    }

    /**
     * Function InsertUserlang
     * 
     * @param $userId is user id
     * @param $lang is language
     * 
     * @return bool is true or false 
     */
    public function insertUserLang(int $userId, array $lang):bool
    {
        if (count($lang) == 0 || empty($userId)) {
            $res = false;
        }
        $rowInserted = 0;
        $sql = "INSERT INTO `user_lang` ( `user_id`, `lang_id`) VALUES (?, ?)";
        foreach($lang as $langId) {
            $stmt = $this->_conn->prepare($sql);
            $stmt->bind_param("is", $userId, $langId);
            $stmt->execute();
            if ($stmt->insert_id > 0 ) {
                $rowInserted += 1; 
            }
        }
        if (count($lang) == $rowInserted) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function insertUserGenre
     * 
     * @param $userId is user id
     * @param $genre is genre
     * 
     * @return bool is true or false
     */
    public function insertUserGenre($userId, $genre):bool
    {
        if (count($genre) == 0 || empty($userId)) {
            $res = false;
        }
        $rowInserted = 0;
        $sql = "INSERT INTO `user_genre` ( `user_id`, `genre_id`) VALUES (?, ?)";
        foreach($genre as $genreId) {
            $addGenreStmt = $this->_conn->prepare($sql);
            $addGenreStmt->bind_param("is", $userId, $genreId);
            $addGenreStmt->execute();
            if ($addGenreStmt->insert_id > 0 ) {
                $rowInserted += 1; 
            }
        }
        if (count($genre) == $rowInserted) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }
}
?>