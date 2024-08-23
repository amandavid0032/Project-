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

namespace Bookxchange\Bookxchange\Controller;

use Bookxchange\Bookxchange\Model\UserM;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Config\SendMail;

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
class User
{
    private $_twig;
    private $_loader;
    private $_mail;
    protected $userM;
    /**
     * Constructor for User.
     *
     * @param $baseurl is baseurl
     */
    public function __construct($baseurl)
    {
        $this->userM = new UserM();
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $mailObj = new SendMail();
        $this->_mail = $mailObj->getSendMailer();
    }

    /**
     * Function logout.
     *
     * @param $userId login user id.
     *
     * @return void nothing
     */
    public function logout(string $userId): void
    {
        $token=null;
        $updateToken = $this->userM->updateToken($userId, $token);
        if ($updateToken == true) {
            session_unset();
            session_destroy();
            $_SESSION['msg'] = "Logout successfully!!";
            header("location:../../index.php");
        } else {
            $_SESSION['msg'] = "Error in logout";
            header("location:dashboard.php");
        }
    }

    /**
     * Function getLogin to get Login
     *
     * @param $phoneOrEmail    phone of user.
     * @param $pass            password.
     * @param $loginFromNumber is true if number , false if email
     *
     * @return void nothing
     */
    public function getLogin(
        string $phoneOrEmail,
        string $pass,
        bool $loginFromNumber
    ): void {
        session_start();
        if ($loginFromNumber === true) {
            $isExist = $this->userM->isPhoneExit($phoneOrEmail);
        } else {
            $isExist = $this->userM->isEmailExit($phoneOrEmail);
        }
        if ($isExist === true) {
            $getLoginDetail = $this->userM->getLogin(
                $phoneOrEmail,
                $loginFromNumber
            );
            if (password_verify($pass, $getLoginDetail['password'])) {
                $token = bin2hex(random_bytes(32));
                $updateToken = $this->userM->updateToken(
                    $getLoginDetail['id'],
                    $token
                );
                $_SESSION['user_id'] = $getLoginDetail['id'];
                $_SESSION['token'] = $token;
                $_SESSION['user_name'] = $getLoginDetail['user_name'];
                $loginName = $_SESSION['user_name'];
                $_SESSION['msg'] = "Welcome $loginName";
                header('location:dashboard.php');
            } else {
                $_SESSION['msg'] = "Invalid Password!";
                header('location:signin.php');
            }
        } else {
            $_SESSION['msg'] = "Account doesnot exist
             with .$phoneOrEmail.'. Please create account!";
            header('location:signin.php');
        }
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
     * @param $lang is language.
     * @param $genre is genre.
     *
     * @return void nothing
     */
    public function getRegister(
        $userImage,
        $userName,
        $userMobile,
        $userAddress,
        $userEmail,
        $userPass,
        $lang,
        $genre
    ): void {
        $isPhoneExit = $this->userM->isPhoneExit($userMobile);
        if ($isPhoneExit === false) {
            $hashPass = password_hash($userPass, PASSWORD_BCRYPT);
            $isRegister = $this->userM->updateRegister(
                $userImage,
                $userName,
                $userMobile,
                $userAddress,
                $userEmail,
                $hashPass
            );
            if ($isRegister == true) {
                $insertOk = $this->insertLangAndGenre($lang, $genre, $userEmail);
                unset($_SESSION['new_email']);
                unset($_SESSION['verify_form']);
                unset($_SESSION['new_id']);
                $this->getLogin($userEmail, $userPass, false);
            } else {
                unset($_SESSION['new_email']);
                unset($_SESSION['verify_form']);
                $_SESSION['msg'] = "Registration failed!";
                header('location:signin.php');
            }
        } else {
            $_SESSION['msg'] = "You have entered
             Phone Number is already exist!";
            header('location:signup.php?formcode='.$_SESSION['verify_form']);
        }
    }

    /**
     * Function getForgetPass give forget control.
     *
     * @param $mobileNo is mobile number of user.
     *
     * @return void nothing
     */
    public function getForgetPass(string $mobileNo): void
    {
        $isPhoneExit = $this->userM->isPhoneExit($mobileNo);
        if ($isPhoneExit === true) {
            $otp = mt_rand(1111, 9999);
            $otpExpire = time()+120;
            $_SESSION['otp'] = $otp;
            $_SESSION['otpExpire'] = $otpExpire;
            $_SESSION['mobile'] = $mobileNo;
            $myfile = fopen("otp.txt", "w") or die("Unable to open file!");
            fwrite($myfile, (string) $otp);
            fclose($myfile);
            $_SESSION['msg'] = "Otp sent..";
            header('location:otpverify.php');
        } else {
            $_SESSION['msg'] = "Phone no. not  exit!";
            header('location:../../index.php');
        }
    }

    /**
     * Function updatePassword update pass.
     *
     * @param $pass   is password.
     * @param $mobile is mobile no.
     *
     * @return bool true or false.
     */
    public function updatePassword(string $pass, string $mobile): bool
    {
        $hashPass = password_hash($pass, PASSWORD_BCRYPT);
        $updatePass = $this->userM->updatePassword($hashPass, $mobile);
        return $updatePass;
    }

    /**
     * Function userProfile show the profile of user.
     *
     * @param $userId user id.
     *
     * @return static twig file.
     */
    public function userProfile(int $userId)
    {
        $userProfile = $this->userM->userProfile($userId);
        return $this->_twig->render('profile.html.twig', ['user'=>$userProfile]);
    }

    /**
     * Function isNumber Exit
     *
     * @param $phone phone number
     *
     * @return bool true or false
     */
    public function isNumberExit(string $phone): bool
    {
        return $this->userM->isPhoneExit($phone);
    }

     /**
      * Function isEmailExit check
      *
      * @param $newUserEmail email of user
      *
      * @return bool true or false
      */
    public function isEmailExit($newUserEmail): bool
    {
        return $this->userM->isEmailExit($newUserEmail);
    }

     /**
      * Function updateProfile
      *
      * @param $newUserImage   user image.
      * @param $newUserName    name of user
      * @param $newUserNumber  number of user.
      * @param $newUserAddress address of user.
      * @param $newUserEmail   email of user.
      * @param $userId         is id of user.
      *
      * @return bool true or false.
      */
    public function updateProfile(
        $newUserImage,
        $newUserName,
        $newUserNumber,
        $newUserAddress,
        $newUserEmail,
        $userId
    ) {
        $updateProfile = $this->userM->updateProfile(
            $newUserImage,
            $newUserName,
            $newUserNumber,
            $newUserAddress,
            $newUserEmail,
            $userId
        );
        return $updateProfile;
    }

    /**
     * Function userRating user rating
     *
     * @param $rating      is rating of user.
     * @param $requesterId is userid.
     *
     * @return void return nothing.
     */
    public function updateUserRating(float $rating, int $requesterId): void
    {
        $this->userM->updateUserRating($rating, $requesterId);
    }

     /**
      * Function resetNewPassword
      *
      * @param $userId  is user id.
      * @param $oldPass is old password.
      * @param $newPass is new password.
      *
      * @return void nothing is returning
      */
    public function resetNewPassword(
        int $userId,
        string $oldPass,
        string $newPass
    ): void {
        $getOldPass = $this->userM->getOldPass($userId);
        $oldPassVerify = password_verify($oldPass, $getOldPass['password']);
        if ($oldPassVerify === true) {
            $hashPass = password_hash($newPass, PASSWORD_BCRYPT);
            $updatePass = $this->userM->updateNewPass($userId, $hashPass);
            if ($updatePass === true) {
                $_SESSION['msg'] = "Password Updated";
            } else {
                $_SESSION['msg'] = "Password not Updated";
            }
        } else {
            $_SESSION['msg'] = "Old password is not correct";
        }
        header('location:profile.php');
    }

    /**
     * Function RegisterEmail
     *
     * @return static twig file.
     */
    public function registerEmail()
    {
        return $this->_twig->render('registerEmail.html.twig');
    }
     /**
      * Function RegisterOtp
      *
      * @return static twig file
      */
    public function registerVerifyOtp()
    {
        return $this->_twig->render('registerVerifyOtp.html.twig');
    }

    /**
     * Function insertRegisterOtp
     *
     * @param $id    is user id
     * @param $email is email
     * 
     * @return void nothing
     */
    public function insertRegisterOtp(int $id, string $email): void
    {
        $isEmailExist = $this->userM->isEmailExit($email);
        if ($isEmailExist == true) {
            $detail = $this->userM->getUserDetailByEmail($email);
            if ($detail['user_name'] == null
                && $detail['mobile_no'] == null
                && $detail['address'] == null 
                && $detail['password'] == null
            ) {
                $isExist = false;
                $id = $detail['id'];
            } else {
                $isExist = true;
            }
        } else {
            $isExist = false;
        }
        if ($isExist === false) {
            $sixDigitCode = random_int(100000, 999999);
            $res = $this->userM->insertRegisterOtp(
                $id, $email, $sixDigitCode
            );
            if ($res != 0) {
                $otpExpire = time()+120;
                $_SESSION['otpExpire'] = $otpExpire;
                $_SESSION['new_email'] = $email;
                $_SESSION['new_id'] = $res;

                $this->_mail->addAddress($email);
                $this->_mail->Subject = 'OTP For Bookxchange Registration';
                $this->_mail->Body    = 'You requested OTP is '.$sixDigitCode;
                //$this->_mail->send()
                $send = 1;
                if ($send == 1) {
                    $_SESSION['msg'] = "We have sent you 6 digit
                     verification code at ".$email." . 
                    It will expire after 2 minutes.
                     Please don't back OTP is ".$sixDigitCode;
                    $_SESSION['verify_otp_form'] = mt_rand(0000, 9999);
                    header(
                        'location:registerVerifyOtp.php?formcode='.
                        $_SESSION['verify_otp_form']
                    );
                } else {
                    $_SESSION['msg'] ="Message could not be sent. 
                    Mailer Error: {$this->_mail->ErrorInfo}";
                    unset($_SESSION['new_email']);
                    header('location:registerEmail.php');
                }
            } else {
                $_SESSION['msg'] = "Error while Generating OTP";
                header('location:registerEmail.php');
            }
        } else {
            $_SESSION['msg'] = " $email is already exist.
             Please, Signup with New Email!";
            header('location:registerEmail.php');
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
    public function getOtpVerify(string $otp, string $email)
    {
        if (isset($_SESSION['otpExpire'])) {
            if (time() <= $_SESSION['otpExpire']) {
                $getOtp = $this->userM->getRegisterOtp($email);
                if ($getOtp['otp'] == $otp) {
                    unset($_SESSION['verify_otp_form']);
                    $_SESSION['verify_form'] = mt_rand(0000, 9999);
                    header('location:signup.php?formcode='.$_SESSION['verify_form']);  
                    $_SESSION['msg'] = 'Please, Fill All field Carefully!!';
                } else {
                    $_SESSION['msg'] = "Please, Enter correct OTP!!";
                    header(
                        'location:registerVerifyOtp.php?formcode='
                        .$_SESSION['verify_otp_form']
                    );
                }
            } else {
                $_SESSION['msg'] = 'Your Time is Expired.
                 Please click on resend the code !';
                header(
                    'location:registerVerifyOtp.php?formcode='
                    .$_SESSION['verify_otp_form']
                );
            }
        }
    }

    /**
     * Function insertlangAndGenre
     * 
     * @param $lang is langauge
     * @param $genre is genre
     * @param  $email is email
     * 
     * @return bool true or false
     */
    public function insertLangAndGenre(array $lang, array $genre, string $email):bool 
    {
        $user = $this->userM->getUserDetailByEmail($email);
        $userId = $user['id'];
        $langOk = $this->userM->insertUserLang($userId, $lang);
        $genreOk = $this->userM->insertUserGenre($userId, $genre);
        if ( $langOk == true && $genreOk == true) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }
}
