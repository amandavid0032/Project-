<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\GetTokenFromDb\GetToken;
use App\Token\GenToken;
use App\Config\SendMail;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

// session_start();

class UserController
{
    protected $conn;
    protected $valToken;
    protected $userModelObj;
    protected $token;
    protected $mailer;
    public function __construct($userModelObj, $conn)
    {
        $this->conn = $conn;
        $this->valToken = new GetToken($this->conn);
        $this->userModelObj = $userModelObj;
        $this->token = new GenToken();
        $this->mailer = new SendMail();
    }

    public function userList(Request $request, Response $response)
    {
        $uList = $this->userModelObj->listUser();
        $jsonMessage = array(
            "isSuccess" => true,
            "message" => "List of users",
            "list" => $uList
        );
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }
    public function addUpdate(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        $params = $request->getParsedBody();
        $name = trim($params['name'] ?? '');
        $mobile_no = trim($params['mobile_no'] ?? '');
        $address = trim($params['address'] ?? '');
        $email = trim($params['email'] ?? '');
        $password = trim($params['password'] ?? '');
        $FCM_token = trim($params['FCM_token'] ?? '');
        $userImgLink = "0";

        if ($userId == 0) { //Adding new user.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $validation = $this->userModelObj->checkEmailAndMobileExists($email, $mobile_no);
            if ($validation) {
                // $tok_val = $this->token->genCSRFTkn();
                if (isset($_FILES['image']) && strlen($_FILES['image']['name']) != 0) {
                    $allowedExt = ['png', 'jpg', 'jpeg'];
                    $imgName = $_FILES['image']['name'];
                    $imgExt = pathinfo($imgName, PATHINFO_EXTENSION);

                    if (in_array($imgExt, $allowedExt)) {
                        $image = $_FILES['image'];
                        $img_name = $image['name'];
                        $img_path = $image['tmp_name'];
                        $dest = __DIR__ . "/../img/users/" . $img_name;
                        $userImgLink = "app/img/users/" . $img_name;
                        move_uploaded_file($img_path, $dest);
                    } else {
                        $jsonMessage = array(
                            "isSuccess" => false,
                            "message" => "Only images are allowed.",
                            "Token" => null,
                            "userId" => null
                        );
                        $response->getBody()->write(json_encode($jsonMessage));
                        return $response
                            ->withHeader("content-type", "application/json")
                            ->withStatus(200);
                    }
                }
                $signRst = $this->userModelObj->signUp($name, $mobile_no, $address, $email, $hashed_password, $userImgLink, "0", $FCM_token);
                if ($signRst) {
                    $loginRst = $this->userModelObj->logIn($mobile_no,$email);
                    if ($loginRst) {
                        $key = 'oxole@ideafoundation.in';
                        $currentTime = time();
                        $payload = [
                            'userId' => $loginRst[1],
                            'userName' => $loginRst[2],
                            'userEmail' => $loginRst[3],
                            'time' => $currentTime
                        ];
                        // $jwt_tok_val = JWT::encode($payload, $key, 'HS256');
                        $jwt_tok_val = "randomTokenForNow";
                        $this->userModelObj->updateJWTToken($mobile_no, $jwt_tok_val);
                        $jsonMessage = array(
                            "isSuccess" => true,
                            "message" => "Registration success",
                            "Token" => $jwt_tok_val,
                            "userId" => $loginRst[1],
                            "userName" => $loginRst[2],
                            "userEmail" => $loginRst[3],
                            "userImage" => $loginRst[4],
                            "phone_no" => $mobile_no,
                        );
                    }
                }
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Phone or email already exists.",
                    "Token" => null,
                    "userId" => null,
                    "userName" => null,
                    "userEmail" => null,
                    "userImage" => null,
                    "phone_no" => null,
                );
            }
            //Updating profile
        } else {
            $getUserImage = $this->userModelObj->getUser($userId);
            $userImgLink = $getUserImage['image'];
            if (isset($_FILES['image']) && strlen($_FILES['image']['name']) != 0) {
                $allowedExt = ['png', 'jpg', 'jpeg'];
                $imgName = $_FILES['image']['name'];
                $imgExt = pathinfo($imgName, PATHINFO_EXTENSION);

                if (in_array($imgExt, $allowedExt)) {
                    $image = $_FILES['image'];
                    $img_name = $image['name'];
                    $img_path = $image['tmp_name'];
                    $dest = __DIR__ . "/../img/users/" . $img_name;
                    $userImgLink = "app/img/users/" . $img_name;
                    move_uploaded_file($img_path, $dest);
                } else {
                    $jsonMessage = array(
                        "isSuccess" => false,
                        "message" => "Only images are allowed.",
                        "Token" => null,
                        "userId" => null,
                        "userName" => null,
                        "userEmail" => null,
                        "userImage" => null,
                        "phone_no" => null,
                    );
                    $response->getBody()->write(json_encode($jsonMessage));
                    return $response
                        ->withHeader("content-type", "application/json")
                        ->withStatus(200);
                }
            }
            $updateRst = $this->userModelObj->updateProfile($userImgLink, $name, $address, $email, $mobile_no, $userId);
            if ($updateRst) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Profile updated Successfully.",
                );
            }
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function logIn(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $mobile_no = trim($params['mobile_no'] ?? '');
        $email = trim($params['email'] ?? '');
        $password = trim($params['password'] ?? '');
        $FCM_token = trim($params['FCM_token'] ?? '');
        $loginRst = $this->userModelObj->logIn($mobile_no, $email);
        if ($loginRst) {
            if (password_verify($password, $loginRst[0])) {
                $key = 'oxole@ideafoundation.in';
                $currentTime = time();
                $payload = [
                    'userId' => $loginRst[1],
                    'userName' => $loginRst[2],
                    'userEmail' => $loginRst[3],
                    'time' => $currentTime
                ];
                // $jwt_tok_val = JWT::encode($payload, $key, 'HS256');
                $jwt_tok_val = "randomTokenForNow";
                $this->userModelObj->addToken($mobile_no ?: $loginRst[5], $FCM_token, $jwt_tok_val);
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Login Success",
                    "Token" => $jwt_tok_val,
                    "userId" => $loginRst[1],
                    "userName" => $loginRst[2],
                    "userEmail" => $loginRst[3],
                    "userImage" => $loginRst[4],
                    "phone_no" => $mobile_no ?: $loginRst[5],
                );
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Password Wrong.",
                    "Token" => null,
                    "userId" => null,
                    "userName" => null,
                    "userEmail" => null,
                    "userImage" => null,
                    "phone_no" => null,
                );
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Mobile no or email is wrong.",
                "Token" => null,
                "userId" => null,
                "userName" => null,
                "userEmail" => null,
                "userImage" => null,
                "phone_no" => null,
            );
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }


    public function logOut(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        $userExistsRst = $this->userModelObj->checkUserExists($userId);
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "User not exists..."
        );
        if ($userExistsRst || $userId == 0) {
            $this->userModelObj->removeToken($userId);
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Logged Out successfully."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function index()
    {
        $file = file("/var/log/apache2/domains/bookexchange.oidea.xyz.error.log");
        for ($i = max(0, count($file) - 6); $i < count($file); $i++) {
            echo $file[$i] . "\n";
        }
        echo "Index Working";
    }



    public function getUserById(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        $userExists = $this->userModelObj->checkUserExists($userId);
        if ($userExists) {
            $getUserDetails = $this->userModelObj->getUser($userId);
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "User details",
                "user" => $getUserDetails
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "User not exists.",
                "user" => null
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
    }

   public function resetPassword(Request $request, Response $response)
{
    $params = $request->getParsedBody();
    $mobile_no = trim($params['mobile_no'] ?? '');
    $email = trim($params['email'] ?? '');
    $old_password = trim($params['old_password'] ?? '');
    $password = trim($params['password'] ?? '');

    $identifier = !empty($mobile_no) ? $mobile_no : $email;
    $userExists = $this->userModelObj->checkUserExists($mobile_no, $email);
    
    if ($userExists) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (empty($old_password) && isset($password)) {
            // Forget Password
            $updatePass = $this->userModelObj->updatePassword($mobile_no, $email, $hashed_password);
            if ($updatePass) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Password updated successfully."
                );
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Something went wrong."
                );
            }
        } elseif (!empty($old_password) && isset($password)) {
            // Password reset
            $userSavedPassword = $this->userModelObj->getPasswordByIdentifier($identifier);
            if (password_verify($old_password, $userSavedPassword)) {
                $updatePass = $this->userModelObj->updatePassword($mobile_no, $email, $hashed_password);
                if ($updatePass) {
                    $jsonMessage = array(
                        "isSuccess" => true,
                        "message" => "Password updated successfully."
                    );
                } else {
                    $jsonMessage = array(
                        "isSuccess" => false,
                        "message" => "Something went wrong."
                    );
                }
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Old password doesn't match."
                );
            }
        }
    } else {
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "Mobile number or email not registered."
        );
    }
    $response->getBody()->write(json_encode($jsonMessage));
    return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
}


    public function getOtp(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $mobile_no = trim($params['mobile_no'] ?? '');
        $mode = (int)trim($params['mode'] ?? 0);
        $fcmToken = $params['token'] ?? '';
        if (strlen($mobile_no) != 10) {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Mobile should be of 10 digits.",
                "OTP" => null
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response->withHeader("content-type", "application/json")->withStatus(200);
        }

        $result = $this->userModelObj->checkMobileExists($mobile_no, $mode);
        if (is_array($result)) {
            $otp = $result[0];
            $userId = $result[1];
            $pushNotification = array(
                "body" => "Your OTP is $otp",
                "title" => "OTP Code",
                "type" => 11,
                "otp" => $otp
            );
            
            $notificationResponse = $this->send_notification($fcmToken, $pushNotification, 'Android');
            $notificationResponseArray = json_decode($notificationResponse, true);
            if (is_array($notificationResponseArray) && isset($notificationResponseArray['success'])) {
                if ($notificationResponseArray['success'] == 1) {
                    $jsonMessage = array(
                        "isSuccess" => true,
                        "message" => "OTP generated and notification sent successfully.",
                        "OTP" => $otp,
                        "userId" => $userId
                    );
                } else {
                    $jsonMessage = array(
                        "isSuccess" => false,
                        "message" => "OTP generated but notification failed to send.",
                        "OTP" => $otp,
                        "userId" => $userId
                    );
                }
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Failed to send notification. Invalid FCM response.",
                    "OTP" => $otp,
                    "userId" => $userId
                );
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => $result,
                "OTP" => null,
                "userId" => null
            );
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response->withHeader("content-type", "application/json")->withStatus(200);
    }

    public function send_notification($registatoin_ids, $notification, $device_type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if ($device_type == "Android") {
            $fields = array(
                'to' => $registatoin_ids,
                'data' => $notification
            );
        } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
        }
        $serverKey = $_ENV['FCM_SERVER_KEY'];
        $headers = [
            'Authorization: key=' .$serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            error_log('Curl failed: ' . curl_error($ch));
            return json_encode(['success' => 0, 'error' => curl_error($ch)]);
        }

        curl_close($ch);
        return $result;
    }


    public function verifyOtp(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userOtp = trim($params['otp'] ?? '');
        $mobile_no = trim($params['mobile_no'] ?? '');
        $dbOtp = $this->userModelObj->getOtp($mobile_no);
        if ($userOtp == $dbOtp) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "OTP verified."
            );
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Mobile or otp wrong."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function getLanguage(Request $request, Response $response, $args)
    {
        $userId = (int)$args['user_id'];
        if ($userId == 0) {
            $lang = $this->userModelObj->getLang();
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of languages.",
                "languages" => $lang
            ];
        } else {
            $userLang = $this->userModelObj->getUserLang($userId);
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of user languages.",
                "languages" => $userLang
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function getGenre(Request $request, Response $response, $args)
    {
        $userId = (int)$args['user_id'];
        if ($userId == 0) {
            $genre = $this->userModelObj->getGenre();
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of Genres.",
                "genres" => $genre
            ];
        } else {
            $userGenre = $this->userModelObj->getUserGenre($userId);
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of user Genres.",
                "genres" => $userGenre
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function submitGenre(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $genreArrString = trim($params['genreArr'] ?? '');
        $userId = (int)trim($params['user_id'] ?? '');
        $genreArr = explode(',', substr($genreArrString, 1, -1));
        $deleteGenreRst = $this->userModelObj->deleteUserGenre($userId);
        $addGenre = $this->userModelObj->submitGenre($genreArr, $userId);
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "User or some genre not found."
        );
        if ($addGenre) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Genres added successfully."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function submitLang(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $langArrString = trim($params['langArr'] ?? '');
        $userId = (int)trim($params['user_id'] ?? '');
        $langArr = explode(',', substr($langArrString, 1, -1));
        $deleteLangRst = $this->userModelObj->deleteUserLang($userId);
        $addLang = $this->userModelObj->submitLang($langArr, $userId);
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "User or language not found."
        );
        if ($addLang) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Languages added successfully."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function deleteProfilePicture(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        $userExistsRst = $this->userModelObj->checkUserExists($userId);
        if ($userExistsRst) {
            $deleteUserProfileRst = $this->userModelObj->updateUserProfile($userId);
            if ($deleteUserProfileRst) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Profile picture deleted successfully"
                );
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "No profile picture found."
                );
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "User doesn't exists."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function deleteAccount(Request $request, Response $response, $args)
    {
        $userId = $args['userId'];
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "Not able to Delete Account because of some pending transaction."
        );
        $pendingTransaction = $this->userModelObj->checkUserPendingTransaction($userId);
        if (!$pendingTransaction) {
            $deleteUserBook =$this->userModelObj->deleteUserBooks($userId);
            $deleteUserReview = $this->userModelObj->deleteUserBelongings($userId, 'user_id', 'feedback');
            $deleteUserRequest = $this->userModelObj->deleteUserBelongings($userId, 'requester_id', 'request');
            $deleteUserRequest = $this->userModelObj->deleteUserBelongings($userId, 'owner_id', 'request');
            $deleteUserGenre = $this->userModelObj->deleteUserBelongings($userId, 'user_id', 'user_genre');
            $deleteUserLang = $this->userModelObj->deleteUserBelongings($userId, 'user_id', 'user_lang');
            $deleteUserWishList = $this->userModelObj->deleteUserBelongings($userId, 'user_id', 'wish_list');
            $deleteUserAccount = $this->userModelObj->deleteUserBelongings($userId, 'id', 'register');
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Account Deleted Successfully."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }


    /**
     * Function insertRegisterOtp
     *
     * @param $id    is user id
     * @param $email is email
     * 
     * @return void nothing
     */
    public function insertRegisterOtp(Request $request, Response $response)
    {
        $params = (array)$request->getParsedBody();
        $email = $params['email'] ?? '';
        $mode = (int)trim($params['mode'] ?? 0);
        $result = $this->userModelObj->checkEmailExists($email, $mode);
        if (is_array($result)) {
            list($otp, $id) = $result;
            $otpExpire = time() + 120;
            $_SESSION['otpExpire'] = $otpExpire;
            $_SESSION['new_email'] = $email;
            $_SESSION['new_id'] = $id;

            $this->mailer->getSendMailer()->addAddress($email);
            $this->mailer->getSendMailer()->Subject = 'OTP For Bookxchange Registration';
            $this->mailer->getSendMailer()->Body = 'Your requested OTP is ' . $otp;

            try {
                $this->mailer->getSendMailer()->send();
                $_SESSION['msg'] = "We have sent you a 6-digit verification code at $email. It will expire after 2 minutes.";
                $_SESSION['verify_otp_form'] = mt_rand(0000, 9999);

                $responseMessage = [
                    'success' => true,
                    'message' => "We have sent you a 6-digit verification code at $email. It will expire after 2 minutes.",
                    'otp' => $otp,
                    'id' => $id
                ];
                $response->getBody()->write(json_encode($responseMessage));
                return $response->withHeader("content-type", "application/json")->withStatus(200);
            } catch (\Exception $e) {
                $_SESSION['msg'] = "Message could not be sent. Mailer Error: " . $this->mailer->getSendMailer()->ErrorInfo;
                unset($_SESSION['new_email']);

                $responseMessage = [
                    'success' => false,
                    'message' => "Message could not be sent. Mailer Error: " . $this->mailer->getSendMailer()->ErrorInfo
                ];
                $response->getBody()->write(json_encode($responseMessage));
                return $response->withHeader("content-type", "application/json")->withStatus(500);
            }
        } else {
            $_SESSION['msg'] = $result;

            $responseMessage = [
                'success' => false,
                'message' => $result
            ];
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withHeader("content-type", "application/json")->withStatus(400);
        }
    }



    /**
     * Function getOtpVerify
     *
     * @param $otp   is otp
     * @param $email is email
     *
     * @return void nothing
     */

    public function getOtpVerify(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $email = trim($params['email'] ?? '');
        $userOtp = trim($params['otp'] ?? '');
        $dbOtp = $this->userModelObj->getOtpemail($email);
        if ($userOtp == $dbOtp) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "OTP verified."
            );
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "email or Opt is wrong ."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }
}
