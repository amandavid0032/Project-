<?php

namespace App\Model;

use PDO;

class UserModel
{
    public const STATUS = 'active';
    public const TOKEN = '0';
    public const USER_TYPE = 0; //for normal user

    //const value for entering new user.
    public const IMAGE = "0";
    public const USER_NAME = "0";
    public const ADDRESS = "0";
    public const EMAIL = "0";
    // public const LATTITUDE = "0";
    // public const LONGITUDE = "0";
    public const PASSWORD = "0";
    // public const RATING = "0";

    public const ISSUED_STATUS = 1;
    public const RETURNING_STATUS = 2;
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkEmailAndMobileExists(string $email, string $mobile_no)
    {
        $checkNumEmailExists = $this->conn->prepare("select * from register where email = ? or mobile_no = ?");
        $checkNumEmailExists->bind_param("ss", $email, $mobile_no);
        $checkNumEmailExists->execute();
        $rst = $checkNumEmailExists->get_result();
        if ($rst->num_rows > 0) {
            $userDetail = $rst->fetch_assoc();
            if ($userDetail['user_name'] != '0') {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function checkUserLoggedIn(string $param)
    {
        if (strlen($param) == 10) {
            $checkLoggedInStmt = $this->conn->prepare("select * from register where mobile_no = ?");
        } else {
            $checkLoggedInStmt = $this->conn->prepare("select * from register where id = ?");
        }
        $checkLoggedInStmt->bind_param("s", $param);
        $checkLoggedInStmt->execute();
        $checkLoggedInRst = $checkLoggedInStmt->get_result();
        $loggedInUserDetail = $checkLoggedInRst->fetch_assoc();
        $loggedInUserToken = $loggedInUserDetail['token'];
        if ($loggedInUserToken == '') {
            return null;
        } else {
            return $loggedInUserToken;
        }
    }

    public function listUser(): array
    {
        $userQuery = $this->conn->prepare("select * from register");
        $userQuery->execute();
        $userList = $userQuery->get_result()->fetch_all(MYSQLI_ASSOC);
        return $userList;
    }

    public function signUp(
        string $name,
        string $mobile_no,
        string $address,
        string $email,
        string $password,
        string $userImg,
        string $token,
        string $fcm_token
    ): bool {
        $status = UserModel::STATUS;
        $userType = UserModel::USER_TYPE;
        $signUpStmt = $this->conn->prepare("update register set image = ?, user_name = ?, mobile_no = ?, address = ?, email = ?, password = ?, status = ?, token = ?, user_type = ?, fcm_token = ? where mobile_no = ?");
        $signUpStmt->bind_param("ssssssssiss", $userImg, $name, $mobile_no, $address, $email, $password, $status, $token, $userType, $fcm_token, $mobile_no);
        $signUpRst = $signUpStmt->execute();
        if ($signUpRst > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateJWTToken(string $mobile_no, string $jwt_tok_val): bool
    {
        $updateTokenStmt = $this->conn->prepare("UPDATE register set token = ? where mobile_no = ?");
        $updateTokenStmt->bind_param("ss", $jwt_tok_val, $mobile_no);
        $updateTokenStmt->execute();
        if ($updateTokenStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function logIn(string $mobile_no = null, string $email = null)
    {
        if ($mobile_no) {
            $loginQry = $this->conn->prepare("SELECT * FROM register WHERE mobile_no = ?");
            $loginQry->bind_param("s", $mobile_no);
        } elseif ($email) {
            $loginQry = $this->conn->prepare("SELECT * FROM register WHERE email = ?");
            $loginQry->bind_param("s", $email);
        } else {
            return false;
        }

        $loginQry->execute();
        $loginRst = $loginQry->get_result();
        $loginValues = $loginRst->fetch_assoc();

        if ($loginRst->num_rows > 0) {
            return [
                $loginValues['password'],
                $loginValues['id'],
                $loginValues['user_name'],
                $loginValues['email'],
                $loginValues['image'],
                $loginValues['mobile_no']
            ];
        } else {
            return false;
        }
    }

    public function addToken(string $mobile_no, string $fcm_token, string $tok_val): bool
    {
        $addTokenQry = $this->conn->prepare("update register set token = ?, fcm_token = ? where mobile_no = ?");
        $addTokenQry->bind_param("sss", $tok_val, $fcm_token, $mobile_no);
        $addTokenQry->execute();
        return true;
    }

    public function removeToken(string $userId): bool
    {
        $tokenVal = '';
        $fcm_token = '';
        $removeTokenRst = $this->conn->prepare("update register set token = ?, fcm_token = ? where id = ?");
        $removeTokenRst->bind_param("sss", $tokenVal, $fcm_token, $userId);
        $removeTokenRst->execute();
        return true;
    }

    public function updateProfile(string $image, string $name, string $address, String $email, String $mobile_no, int $user_id): bool
    {
        $updateQry = $this->conn->prepare("UPDATE register set image = ?, user_name = ?, address = ?, email = ?, mobile_no = ? where id = ?");
        $updateQry->bind_param("sssssi", $image, $name, $address, $email, $mobile_no, $user_id);
        $updateQry->execute();
        return true;
    }

    public function checkUserExists($param)
    {
        if (is_int($param)) {
            $query = "SELECT * FROM register WHERE id = ?";
            $checkUserExistStmt = $this->conn->prepare($query);
            $checkUserExistStmt->bind_param("i", $param);
        } elseif (is_string($param) && strlen($param) == 10) {
            $query = "SELECT * FROM register WHERE mobile_no = ?";
            $checkUserExistStmt = $this->conn->prepare($query);
            $checkUserExistStmt->bind_param("s", $param);
        } elseif (is_string($param)) {
            $query = "SELECT * FROM register WHERE email = ?";
            $checkUserExistStmt = $this->conn->prepare($query);
            $checkUserExistStmt->bind_param("s", $param);
        } else {
            return false;
        }

        $checkUserExistStmt->execute();
        $result = $checkUserExistStmt->get_result();
        $exists = $result->num_rows > 0;
        $checkUserExistStmt->close();

        return $exists;
    }



    public function getUser(int $userId)
    {
        $getUserStmt = $this->conn->prepare("select * from register where id = ?");
        $getUserStmt->bind_param("i", $userId);
        $getUserStmt->execute();
        $user = $getUserStmt->get_result();
        if ($user->num_rows > 0) {
            $rst = $user->fetch_assoc();
            return $rst;
        } else {
            return false;
        }
    }

    public function updatePassword(string $mobile_no, string $email, string $hashed_password)
    {
        if (!empty($mobile_no)) {
            $updatePassStmt = $this->conn->prepare("UPDATE register SET password = ? WHERE mobile_no = ?");
            $updatePassStmt->bind_param("ss", $hashed_password, $mobile_no);
        } elseif (!empty($email)) {
            $updatePassStmt = $this->conn->prepare("UPDATE register SET password = ? WHERE email = ?");
            $updatePassStmt->bind_param("ss", $hashed_password, $email);
        } else {
            return false;
        }
        return $updatePassStmt->execute();
    }

    public function getPasswordByIdentifier(string $identifier): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $getPasswordStmt = $this->conn->prepare("SELECT password FROM register WHERE email = ?");
            $getPasswordStmt->bind_param("s", $identifier);
        } else {
            $getPasswordStmt = $this->conn->prepare("SELECT password FROM register WHERE mobile_no = ?");
            $getPasswordStmt->bind_param("s", $identifier);
        }
        $getPasswordStmt->execute();
        $result = $getPasswordStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result[0]['password'];
    }


    public function setOtp(String $mobile_no): int
    {
        $otp = (int)rand(1000, 9999);
        $setOtpStmt = $this->conn->prepare("insert into otp(mobile_no, otp) values(?, ?)");
        $setOtpStmt->bind_param("si", $mobile_no, $otp);
        $setOtpStmt->execute();
        if ($setOtpStmt->insert_id > 0) {
            return $otp;
        } else {
            return false;
        }
    }

    public function getOtp(String $mobile_no)
    {
        $getOtpStmt = $this->conn->prepare("select * from register where mobile_no = ?");
        $getOtpStmt->bind_param("s", $mobile_no);
        $getOtpStmt->execute();
        $getOtpRst = $getOtpStmt->get_result();
        if ($getOtpRst->num_rows > 0) {
            $dbOtp = $getOtpRst->fetch_assoc();
            return $dbOtp['otp'];
        } else {
            return false;
        }
    }

    public function getUserSelectedLangAndGenre(string $table, int $userId)
    {
        $selectedArray = [];
        $getSelectedStmt = $this->conn->prepare("select * from $table where user_id = ?");
        $getSelectedStmt->bind_param('i', $userId);
        $getSelectedStmt->execute();
        $getSelectedRst = $getSelectedStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if ($table == 'user_lang') {
            foreach ($getSelectedRst as $lang) {
                array_push($selectedArray, $lang['lang_id']);
            }
        } elseif ($table == 'user_genre') {
            foreach ($getSelectedRst as $genre) {
                array_push($selectedArray, $genre['genre_id']);
            }
        }
        return $selectedArray;
    }

    public function getLang()
    {
        $langnguage = [];
        $allLanguage = [];
        $getLangStmt = $this->conn->prepare("select * from language");
        $getLangStmt->execute();
        $getLangRst = $getLangStmt->get_result();
        $getLang = $getLangRst->fetch_all(MYSQLI_ASSOC);
        foreach ($getLang as $lang) {
            $langnguage['id'] = $lang['id'];
            $langnguage['name'] = $lang['name'];
            $langnguage['isSelected'] = false;
            array_push($allLanguage, $langnguage);
        }
        return $allLanguage;
    }

    public function getUserLang(int $userId)
    {
        $langnguage = [];
        $allLanguage = [];
        $langId = $this->getUserSelectedLangAndGenre('user_lang', $userId);
        $getUserLangStmt = $this->conn->prepare("select * from language");
        $getUserLangStmt->execute();
        $getUserLangRst = $getUserLangStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($getUserLangRst as $lang) {
            $langnguage['id'] = $lang['id'];
            $langnguage['name'] = $lang['name'];
            $langnguage['isSelected'] = false;
            if (in_array($lang['id'], $langId)) {
                $langnguage['isSelected'] = true;
            }
            array_push($allLanguage, $langnguage);
        }
        return $allLanguage;
    }

    public function getGenre()
    {
        $genre = [];
        $allGenre = [];
        $getGenreStmt = $this->conn->prepare("select * from genre order by genre");
        $getGenreStmt->execute();
        $getGenreRst = $getGenreStmt->get_result();
        $getGenre = $getGenreRst->fetch_all(MYSQLI_ASSOC);
        foreach ($getGenre as $Genre) {
            $genre['id'] = $Genre['id'];
            $genre['genre'] = $Genre['genre'];
            $genre['isSelected'] = false;
            array_push($allGenre, $genre);
        }
        return $allGenre;
    }

    public function getUserGenre(int $userId)
    {
        $genre = [];
        $allGenre = [];
        $genreId = $this->getUserSelectedLangAndGenre('user_genre', $userId);
        $getUserGenreStmt = $this->conn->prepare("select * from genre order by genre");
        $getUserGenreStmt->execute();
        $getUserGenreRst = $getUserGenreStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($getUserGenreRst as $lang) {
            $genre['id'] = $lang['id'];
            $genre['genre'] = $lang['genre'];
            $genre['isSelected'] = false;
            if (in_array($genre['id'], $genreId)) {
                $genre['isSelected'] = true;
            }
            array_push($allGenre, $genre);
        }
        return $allGenre;
    }

    public function checkMobileExists(string $mobile_no, int $mode)
    {
        $otp = (int)rand(1000, 9999);
        $msg = '';

        $image = UserModel::IMAGE;
        $user_name = UserModel::USER_NAME;
        $address = UserModel::ADDRESS;
        $email = UserModel::EMAIL;
        // $lattitude = UserModel::LATTITUDE;
        // $longitude = UserModel::LONGITUDE;
        $password = UserModel::PASSWORD;
        // $rating = UserModel::RATING;
        $status = UserModel::STATUS;
        $token = UserModel::TOKEN;
        $user_type = UserModel::USER_TYPE;

        $checkMobileExistStmt = $this->conn->prepare("select * from register where mobile_no = ?");
        $checkMobileExistStmt->bind_param("s", $mobile_no);
        $checkMobileExistStmt->execute();
        $rst = $checkMobileExistStmt->get_result();

        if ($mode == 0) { // 0 for signup
            if ($rst->num_rows > 0) {
                $userDetails = $rst->fetch_assoc();
                if ($userDetails['user_name'] == '0') {
                    $updateNewRecordStmt = $this->conn->prepare("update register set otp = ? where mobile_no = ?");
                    $updateNewRecordStmt->bind_param("is", $otp, $mobile_no);
                    $updateNewRecordStmt->execute();

                    $getUserIdStmt = $this->conn->prepare("select * from register where mobile_no = ?");
                    $getUserIdStmt->bind_param("s", $mobile_no);
                    $getUserIdStmt->execute();
                    $userDetail = $getUserIdStmt->get_result()->fetch_assoc();
                    return [$otp, $userDetail['id']];
                } else {
                    $msg = "Mobile already exists";
                    return $msg;
                }
            } else {
                $addNewRecordStmt = $this->conn->prepare("insert into register (image, user_name, mobile_no, address, email, password, status, token, otp, user_type) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $addNewRecordStmt->bind_param("ssssssssii", $image, $user_name, $mobile_no, $address, $email, $password, $status, $token, $otp, $user_type);
                $addNewRecordStmt->execute();
                $userId = $addNewRecordStmt->insert_id;
                return [$otp, $userId];
            }
        } elseif ($mode == 1) { //1 for update
            if ($rst->num_rows == 1) {
                $updateOtpStmt = $this->conn->prepare("update register set otp = ? where mobile_no = ?");
                $updateOtpStmt->bind_param("is", $otp, $mobile_no);
                $updateOtpStmt->execute();
                $userId = $updateOtpStmt->insert_id;
                return [$otp, $userId];
            } else {
                $msg = "Mobile not exists";
                return $msg;
            }
        }
    }

    public function submitGenre(array $genreArr, int $userId)
    {
        if (count($genreArr) == 0 || empty($userId)) {
            return false;
        }
        $rowInserted = 0;
        foreach ($genreArr as $genreId) {
            $addGenreStmt = $this->conn->prepare("INSERT INTO `user_genre` ( `user_id`, `genre_id`) VALUES (?, ?);");
            $addGenreStmt->bind_param("is", $userId, $genreId);
            $addGenreStmt->execute();
            if ($addGenreStmt->insert_id > 0) {
                $rowInserted += 1;
            }
        }
        if (count($genreArr) == $rowInserted) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserGenre(int $userId): bool
    {
        $deleteGenreStmt = $this->conn->prepare("DELETE from user_genre where user_id = ?");
        $deleteGenreStmt->bind_param("i", $userId);
        $deleteGenreStmt->execute();
        return true;
    }


    public function submitLang(array $langArr, int $userId)
    {
        if (count($langArr) == 0 || empty($userId)) {
            return false;
        }
        $rowInserted = 0;
        foreach ($langArr as $langId) {
            $addGenreStmt = $this->conn->prepare("INSERT INTO `user_lang` ( `user_id`, `lang_id`) VALUES (?, ?);");
            $addGenreStmt->bind_param("is", $userId, $langId);
            $addGenreStmt->execute();
            if ($addGenreStmt->insert_id > 0) {
                $rowInserted += 1;
            }
        }
        if (count($langArr) == $rowInserted) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserLang(int $userId): bool
    {
        $deleteLangStmt = $this->conn->prepare("DELETE from user_lang where user_id = ?");
        $deleteLangStmt->bind_param("i", $userId);
        $deleteLangStmt->execute();
        return true;
    }

   

    public function updateUserProfile(int $userId): bool
    {
        $imageNull = '';
        $deleteUserProfileStmt = $this->conn->prepare("update register set image = ? where id = ?");
        $deleteUserProfileStmt->bind_param("si", $imageNull, $userId);
        $deleteUserProfileStmt->execute();
        if ($deleteUserProfileStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserPendingTransaction(int $userId): bool
    {
        $issued = BookModel::ISSUED_STATUS;
        $returning = BookModel::RETURNING_STATUS;
        $deleteAccountStmt = $this->conn->prepare(
            "select id
            from request
            where (requester_id = ? OR owner_id = ?) and (status = ? OR status = ?)"
        );
        $deleteAccountStmt->bind_param("iiii", $userId, $userId, $issued, $returning);
        $deleteAccountStmt->execute();
        $rst = $deleteAccountStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return (count($rst) > 0) ? true : false;
    }

    public function deleteUserBelongings(int $userId, string $columnName, string $tableName): bool
    {
        $deleteUserBelongingsStmt = $this->conn->prepare("DELETE FROM $tableName where $columnName = $userId");
        $deleteUserBelongingsStmt->execute();
        return true;
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
        $stmt = $this->conn->prepare($sql);
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
            $stmt = $this->conn->prepare($sql);
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
            $stmt = $this->conn->prepare($sql);
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
        $stmt = $this->conn->prepare($sql);
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
     * Function getOtpemail
     *
     * @param $email is email
     *
     * @return array otp
     */

    public function getOtpemail(String $email)
    {
        $getOtpStmt = $this->conn->prepare("select * from register where email = ?");
        $getOtpStmt->bind_param("s", $email);
        $getOtpStmt->execute();
        $getOtpRst = $getOtpStmt->get_result();
        if ($getOtpRst->num_rows > 0) {
            $dbOtp = $getOtpRst->fetch_assoc();
            return $dbOtp['otp'];
        } else {
            return false;
        }
    }

    public function checkEmailExists(string $email, int $mode)
{
    $otp = (int)rand(1000, 9999);
    $msg = '';
    $image = UserModel::IMAGE;
    $user_name = UserModel::USER_NAME;
    $address = UserModel::ADDRESS;
    // $mobile_no = UserModel::default_mobile;
    // $lattitude = UserModel::LATTITUDE;
    // $longitude = UserModel::LONGITUDE;
    $password = UserModel::PASSWORD;
    // $rating = UserModel::RATING;
    $status = UserModel::STATUS;
    $token = UserModel::TOKEN;
    $user_type = UserModel::USER_TYPE;

    $checkEmailExistStmt = $this->conn->prepare("select * from register where email = ?");
    $checkEmailExistStmt->bind_param("s", $email);
    $checkEmailExistStmt->execute();
    $rst = $checkEmailExistStmt->get_result();

    if ($mode == 0) { // 0 for signup
        if ($rst->num_rows > 0) {
            $userDetails = $rst->fetch_assoc();
            if ($userDetails['user_name'] == '0') {
                $updateNewRecordStmt = $this->conn->prepare("update register set otp = ? where email = ?");
                $updateNewRecordStmt->bind_param("is", $otp, $email);
                $updateNewRecordStmt->execute();
                $getUserIdStmt = $this->conn->prepare("select * from register where email = ?");
                $getUserIdStmt->bind_param("s", $email);
                $getUserIdStmt->execute();
                $userDetail = $getUserIdStmt->get_result()->fetch_assoc();
                return [$otp, $userDetail['id']];
            } else {
                $msg = "Email already exists";
                return $msg;
            }
        } else {
            $addNewRecordStmt = $this->conn->prepare("insert into register (image, user_name, email, address, password, status, token, otp, user_type) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $addNewRecordStmt->bind_param("sssssssii", $image, $user_name, $email, $address,  $password, $status, $token, $otp, $user_type);
            $addNewRecordStmt->execute();
            $userId = $addNewRecordStmt->insert_id;
            return [$otp, $userId];
        }
    } elseif ($mode == 1) { //1 for update
        if ($rst->num_rows == 1) {
            $updateOtpStmt = $this->conn->prepare("update register set otp = ? where email = ?");
            $updateOtpStmt->bind_param("is", $otp, $email);
            $updateOtpStmt->execute();
            $userId = $updateOtpStmt->insert_id;
            return [$otp, $userId];
        } else {
            $msg = "Email not exists";
            return $msg;
        }
    }
}

    public function deleteUserBooks(int $userId)
    {
        $bookIds = $this->fetchBookIdsByOwner($userId);

        foreach ($bookIds as $bookId) {
            $this->reassignOrDeleteBookCopies($bookId);
        }

        // Now delete the books owned by the user
        $this->deleteRecordsByColumn('books', 'owner_id', $userId);
    }

    private function reassignOrDeleteBookCopies(int $bookId)
    {
        $copyDetails = $this->fetchFirstBookCopy($bookId);

        if ($copyDetails) {
            $firstCopyId = $copyDetails['id'];
            $newOwnerId = $copyDetails['owner_id'];

            // Update the owner_id in the books table
            $this->updateBookOwner($newOwnerId, $bookId);

            // Delete the first entry in book_copies for this book
            $this->deleteRecordById('book_copies', $firstCopyId);
        } else {
            // Delete the book if there are no copies
            $this->deleteRecordById('books', $bookId);
        }
    }

    private function fetchBookIdsByOwner(int $ownerId): array
    {
        $stmt = $this->conn->prepare("SELECT id FROM books WHERE owner_id = ?");
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookIds = [];

        while ($row = $result->fetch_assoc()) {
            $bookIds[] = $row['id'];
        }

        return $bookIds;
    }

    private function fetchFirstBookCopy(int $bookId): ?array
    {
        $stmt = $this->conn->prepare("SELECT id, owner_id FROM book_copies WHERE book_id = ? ORDER BY upload_date ASC LIMIT 1");
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ?: null;
    }

    private function updateBookOwner(int $newOwnerId, int $bookId)
    {
        $stmt = $this->conn->prepare("UPDATE books SET owner_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $newOwnerId, $bookId);
        $stmt->execute();
    }

    private function deleteRecordById(string $tableName, int $id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $tableName WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    private function deleteRecordsByColumn(string $tableName, string $columnName, int $columnValue)
    {
        $stmt = $this->conn->prepare("DELETE FROM $tableName WHERE $columnName = ?");
        $stmt->bind_param("i", $columnValue);
        $stmt->execute();
    }

   


}
