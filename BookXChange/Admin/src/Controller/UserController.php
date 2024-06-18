<?php
/**
 * Usercontroller that controls all the User related functionality
 *
 * PHP version 7.4.30
 *
 * @category   Book_Apllication
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  2021-2022 Ajeet Tharu
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
namespace Book\Bookxchange\Controller;


require_once __DIR__ .'/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * User controller that controls all the functions of the user
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
class UserController
{
    private $_loader;
    private $_twig;


    /**
     * Constructor for the User controller.
     * 
     * @param $user_m is the object for user model to connect with the database.
     */
    public function __construct($user_m)
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../view/templates');
        $this->twig = new Environment($this->loader);
        $this->user_m = $user_m;
    }

    /**
     * Function to get list of all users.
     * 
     * @return return the list of all the user to the twig file.
     */
    public function getUsers()
    {
        $u_list = $this->user_m->getUsersModel();
        return $this->twig->render('user_list.html.twig', ['u_array' => $u_list]);
    }

    /**
     * Function to get the user loggedOut
     * 
     * @return nothing to return.
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('location:../../index.php');
    }

    /**
     * LogInForm function that displays the loginForm in the landing page
     * 
     * @return returns the loginForm from the twig.
     */
    public function logInForm()
    {
        $welcome = $this->user_m->getWelcomeModel();
        return $this->twig->render('logInForm.html.twig', ['welcome' => $welcome]);

    }

    /**
     * Function to get the user log In
     * 
     * @param $uname is the string value that holds the username
     * @param $upass is the string, that holds the password.
     * 
     * @return nothing to return. 
     */
    public function login($uname, $upass)
    {
        // global $admin_m;
        $userNameResult = $this->user_m->checkUserModel($uname);
        if ($userNameResult) {
            $userPassResult = $this->user_m->getPassModel($uname);
            if (password_verify($upass, $userPassResult['password'])) {
                $_SESSION['login'] = "success";
                $_SESSION['ok'] = "done";
                if ($uname == "superadmin") {
                    $_SESSION['loggedIn'] = "superadmin";
                } elseif ($uname == "bookManager") {
                    $_SESSION['loggedIn'] = "bookManager";
                } elseif ($uname == "userManager") {
                    $_SESSION['loggedIn'] = "userManager";
                }
                header('location:welcome.php');
            } else {
                $_SESSION['wrong'] = "Invalid credentials";
                header('location:../../index.php');
                exit;
            }

        } else {
            $_SESSION['wrong'] = "Username doesnot exit";
            header('location:../../index.php');
            exit;
        }
    }


    /**
     * Function to get all the details of book and users in summary.
     * 
     * @return all the data to the twig file.
     */
    public function getAllData()
    {
        $userData = $this->user_m->getUserDataModel();
        $bookData = $this->user_m->getBookDataModel();
        return $this->twig->render(
            'all_data.html.twig', ['userData' => $userData, 'bookData' => $bookData]
        );

    }

    /**
     * Function to get the user profile.
     * 
     * @param $id integer value for the user whose profile need to be viewed.
     * 
     * @return returns to data to user profile to the twig file. 
     */
    public function userProfile(int $id)
    {

        $userDetails = $this->user_m->getUserDetails($id);
        //fetching book details for that user.
        $userBookProfile = $this->user_m->userBookDetailsModel($id);
        $original_date = $userDetails['join_date'];
        // Creating timestamp from given date
        $timestamp = strtotime($original_date);
        // Creating new date format from that timestamp
        $joining_date = date("d-m-Y", $timestamp);
        return $this->twig->render(
            'user_profile.html.twig', [
                'userBookProfile' => $userBookProfile,
                'userDetails' => $userDetails,
                'joining_date' => $joining_date
            ]
        );
        


    }

    /**
     * Function to delete the user
     * 
     * @param $id is the unique id of the user to delete the data of 
     *            that particular user.
     * 
     * @return nothign to return.
     */
    public function deleteUser(int $id)
    {
        $dltRst = $this->user_m->deleteUserModel($id);
        if ($dltRst) {
            $_SESSION['success'] = "user Deleted successfully";
            header('location:user_list.php');
        } else {
            $_SESSION['fail'] = "Some problem occured. please try again.";
            header('location:user_list.php');
        }
    }

    /**
     * Function to block the user
     * 
     * @param $id is the integer value that holds the id of the
     *            user that needs to be blocked.
     * 
     * @return nothing to return.
     */
    public function blockUser(int $id)
    {
        $blkRst = $this->user_m->blockUserModel($id);
        if ($blkRst) {
            $_SESSION['success'] = "user Blocked successfully";
            header('location:user_list.php');
        } else {
            $_SESSION['fail'] = "Some problem occured. please try again.";
            header('location:user_list.php');
        }

    }

    /**
     * Function to show edit the user form.
     * 
     * @param $id is the id of the user whose data needs to be edited.
     * 
     * @return returns the data to the twig file, including data of the user.
     */
    public function editUserForm(int $id)
    {
        $editUser = $this->user_m->editUserFormModel($id);
        // echo "<pre>";
        // print_r($editUser);
        return $this->twig->render(
            'edit_user_form.html.twig', ['editUser' => $editUser]
        );

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
    public function updateUser(
        int $id,string $uName,string $uMobile, string $uAddress,
        string $uEmail
    ) {
        $updateRst = $this->user_m->updateUserModel(
            $id, $uName, $uMobile, $uAddress, $uEmail
        );
        if ($updateRst) {
            $_SESSION['success'] = "User updated successfully";
            header('location:user_list.php');
        } else {
            $_SESSION['fail'] = "Not update please try again";
            header('location:user_list.php');

        }
    }

    /**
     * Function to unblock the user
     * 
     * @param $id is the integer value that holds the id of the
     *            user which needs to be unblocked.
     * 
     * @return nothing to return.
     */
    public function unBlockUser(int $id)
    {
        $ubBlkRst = $this->user_m->unBlockUserModel($id);
        if ($ubBlkRst) {
            $_SESSION['success'] = "successfully unblocked";
            header('location:user_list.php');
        }
    }
}