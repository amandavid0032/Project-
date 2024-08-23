<?php
/**
 * Action class handle  action type.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

require '../../vendor/autoload.php';
use Bookxchange\Bookxchange\Config\Baseurl;
use Bookxchange\Bookxchange\Controller\User;
use Bookxchange\Bookxchange\Controller\Book;
use Bookxchange\Bookxchange\Controller\Home;
use Bookxchange\Bookxchange\Controller\Dashboard;

session_start();
$base = new Baseurl();
$baseurl = $base->getBaseurl();
$book = new Book($baseurl);
$user = new User($baseurl);
$home = new Home($baseurl);
$dashboard = new Dashboard($baseurl);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get login
    if (isset($_POST['login'])) {
        $loginFromNumber = true;
        $phoneOrEmail = $_POST['phone_no_or_email'];
        if (filter_var($phoneOrEmail, FILTER_VALIDATE_EMAIL)) {
            $loginFromNumber = false;
        }
        $pass = $_POST['pass'];
        if ($phoneOrEmail != null && $pass != null) {
            $user->getLogin($phoneOrEmail, $pass, $loginFromNumber);
        } else {
            $_SESSION['msg'] = "Please fill all the required field!!";
            header('location:signin.php');
        }
    }
    //getsingup
    if (isset($_POST['signup'])) {
        $newUserImage = 'user_image.jpg';
        if (isset($_FILES['user_img'])) {
            if ($_FILES['user_img']['size']>0) {
                $userImage = $_FILES['user_img']['name'];
                $userImageTemp = $_FILES['user_img']['tmp_name'];
                $imgType = strtolower(pathinfo($userImage, PATHINFO_EXTENSION));
                $randomno = rand(0, 100000);
                $generateName = 'user'.date('Ymd').$randomno;
                $generateUserImage = $generateName.'.'.$imgType;
                $desImage='../Upload/Users/'.$generateUserImage;
                move_uploaded_file($userImageTemp, $desImage);
                $newUserImage = $generateUserImage;
            }
        }
        $firstName = $_POST['firstname'];
        $lastName = isset($_POST['lastname']) ? $_POST['lastname'] : null;
        $userName = $firstName." ".$lastName;
        $userMobile = $_POST['phone']['main'];
        $userAddress = $_POST['address'];
        $userEmail = $_SESSION['new_email'];    
        $userPass = $_POST['password'];
        $lang = $_POST['lang'];
        $genre = $_POST['genre'];
        if ($userName != null
            &&  $userMobile != null
            && $userAddress != null
            && $userEmail != null
            && $userPass != null
            && $lang != null
            && $genre != null
        ) {
            $user->getRegister(
                $newUserImage,
                $userName,
                $userMobile,
                $userAddress,
                $userEmail,
                $userPass,
                $lang,
                $genre
            );
        } else {
            $_SESSION['msg'] = "Please fill all the required field!!";
            header('location:signup.php');
        }
    }

    // add new books
    if (isset($_POST['addbook'])) {
        $bookImage = $_FILES['book_image']['name'];
        $tempBookImage = $_FILES['book_image']['tmp_name'];
       
        $imgType = strtolower(pathinfo($bookImage, PATHINFO_EXTENSION));
        
        $randomno = rand(0, 100000);
        $generateName = 'book'.date('Ymd').$randomno;
        $newBookImage = $generateName.'.'.$imgType;
        $desImage='../Upload/Books/'.$newBookImage;
        move_uploaded_file($tempBookImage, $desImage);
        $bookName = $_POST['book_name'];
        $bookGenre = $_POST['book_genre'];
        $bookAuthor = $_POST['book_author'];
        $bookEdition = $_POST['book_edition'];
        $bookPublisher = $_POST['book_publisher'];
        $bookIsbn = $_POST['book_isbn'];
        $bookDes = $_POST['book_des'];
        $bookRating = isset($_POST['rating']) ? $_POST['rating'] : 0;
        $ownerId = $_SESSION['user_id'];
        $review = isset($_POST['book_review']) ? $_POST['book_review'] : null;
        $bookCon = $_POST['bookcondition'];
        $bookLang = $_POST['book_language'];
        if ($bookName != null
            && $bookGenre != null
            && $bookAuthor != null
            && $bookAuthor != null
            && $bookEdition != null
            && $bookPublisher != null
            && $bookIsbn != null
            && $bookDes != null
            && $bookLang != null
            && $bookCon != null
        ) {
            $addNewBook = $book->addNewBook(
                $newBookImage,
                $bookName,
                $bookGenre,
                $bookAuthor,
                $bookEdition,
                $bookPublisher,
                $bookIsbn,
                $bookDes,
                $bookLang,
                $bookCon,
                $bookRating,
                $ownerId
            );
            if ($addNewBook != 0) {
                $insertFeedback = $book->insertBookFeedback(
                    $addNewBook,
                    $review,
                    $ownerId
                );
                if ($insertFeedback === true) {
                    $_SESSION['msg'] = "You uploaded $bookName";
                    header('location:personalBook.php');
                } else {
                    $_SESSION['msg'] = "There is error while inserting feedback";
                    header('location:personalBook.php');
                }
            } else {
                $_SESSION['msg'] = "Book not uploaded. Please add again!";
                header('location:addbook.php');
            }
        } else {
            $_SESSION['msg'] = "Please, fill required field properly!";
            header('location:addbook.php');
        }
    }
    // submit feedback
    if (isset($_POST['feed-submit'])) {
        $bookId = $_POST['book_id'];
        $feedback = $_POST['feedback'];
        $userid = $_SESSION['user_id'];
        $book->insertBookFeedback($bookId, $feedback, $userid);
    }
    // get new register otp
    if (isset($_POST['get_register_otp'])) {
        $email = $_POST['email'];
        $id = 0;
        if ($email != null) {
            $user->insertRegisterOtp($id, $email);
        } else {
            $_SESSION['msg'] = "Please, Fill Required Email field!";
            header('location:registerEmail.php');
        }
    }
    //new regtister otp submit
    if (isset($_POST['otp_submit'])) {
        $otp = $_POST['register_otp'];
        $user->getOtpVerify($otp, $_SESSION['new_email']);
    }
    // for reset new password
    if (isset($_POST['reset_new_password'])) {
        $userId = $_SESSION['user_id'];
        $oldPass = $_POST['old_user_password'];
        $newPass = $_POST['new_user_password'];
        $user->resetNewPassword($userId, $oldPass, $newPass);
    }
    //for update profile
    if (isset($_POST['updateprofile'])) {
        $oldImage = $_POST['old_image'];
        $firstName =  $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $oldNumber = $_POST['old_number'];
        $oldEmail = $_POST['old_email'];
        $newUserImage = $oldImage;
        if (isset($_FILES['newimage'])) {
            if ($_FILES['newimage']['size']>0) {
                $newUserImg = $_FILES['newimage']['name'];
                $imgType = strtolower(pathinfo($newUserImg, PATHINFO_EXTENSION));
                $randomno = rand(0, 100000);
                $generateName = 'user'.date('Ymd').$randomno;
                $generateUserImage = $generateName.'.'.$imgType;
                $desImage='../Upload/Users/'.$generateUserImage;
                move_uploaded_file($_FILES['newimage']['tmp_name'], $desImage);
                $newUserImage = $generateUserImage;
            }
        }
        $newUserName = $firstName.' '.$lastName;
        $newUserNumber = $_POST['user_phone'];
        $newUserAddress = $_POST['user_address'];
        $newUserEmail = $_POST['user_email'];
        $ok = 1;
        if ($newUserNumber == $oldNumber) {
            $newUserNumber = $oldNumber;
        } else {
            $isNumberExit = $user->isNumberExit($newUserNumber);

            if ($isNumberExit == 1) {
                $_SESSION['msg'] = "Number is aleady exit";
                $ok = 0;
            }
        }
        if ($newUserEmail == $oldEmail) {
            $newUserEmail = $oldEmail;
        } else {
            $isEmailExit = $user->isEmailExit($newUserEmail);
            if ($isEmailExit == 1) {
                $_SESSION['msg'] = "Email is aleady exit";
                $ok = 0;
            }
        }
        if ($ok == 1) {
            $isProfileUpdate = $user->updateProfile(
                $newUserImage,
                $newUserName,
                $newUserNumber,
                $newUserAddress,
                $newUserEmail,
                $_SESSION['user_id']
            );
            if ($isProfileUpdate == true) {
                $_SESSION['msg'] = "Updated successfully!";
                header('location:profile.php');
            } else {
                $_SESSION['msg'] = "No Update";
                header('location:profile.php');
            }
        } else {
            header('location:profile.php');
        }
    }
    // set user rating
    if (isset($_POST['userrating'])) {
        $requesterId = $_POST['requesterid'];
        $rating = $_POST['requester_rating'];
        $bookId = $_POST['bookid'];
        $ownerId = $_POST['ownerid'];
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $user->updateUserRating($rating, $requesterId);
        $book->updateRequest($requesterId, $bookId, $ownerId, $status, $reason);
    }
    if (isset($_POST['requestgrand'])) {
        $requesterId = $_POST['requesterid'];
        $bookId = $_POST['bookid'];
        $ownerId = $_POST['ownerid'];
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $book->updateRequest($requesterId, $bookId, $ownerId, $status, $reason);
    }
    // for edit book
    if (isset($_POST['editbook'])) {
        $old_image = $_POST['old_book_image'];
        $newBookImage = $old_image;
        if (isset($_FILES['book_image'])) {
            if ($_FILES['book_image']['size']>0) {
                $newBookImg = $_FILES['book_image']['name'];
                $imgType = strtolower(pathinfo($newBookImg, PATHINFO_EXTENSION));
                $randomno = rand(0, 100000);
                $generateName = 'book'.date('Ymd').$randomno;
                $generateBookImage = $generateName.'.'.$imgType;
                $desImage='../Upload/Books/'.$generateBookImage;
                move_uploaded_file($_FILES['book_image']['tmp_name'], $desImage);
                $newBookImage = $generateBookImage;
            }
        }
        $bookName = $_POST['book_name'];
        $bookId = $_POST['book_id'];
        $bookGenre = $_POST['book_genre'];
        $bookAuthor = $_POST['book_author'];
        $bookEdition = $_POST['book_edition'];
        $bookDes = $_POST['book_des'];
        $bookLanguage = $_POST['book_language'];
        $bookRating = $_POST['rating'];
        $bookCondition = $_POST['bookcondition'];
        $bookPublisher = $_POST['book_publisher'];
        $bookIsbn = $_POST['book_isbn'];
        $ownerId = $_SESSION['user_id'];
        if ($bookId != null
            && $newBookImage != null
            && $bookName != null
            && $bookGenre != null
            && $bookAuthor != null
            && $bookEdition != null
            && $bookAuthor != null
            && $bookPublisher != null
            && $bookIsbn != null
            && $bookDes != null
            && $bookRating != null
            && $bookCondition != null
            && $bookLanguage != null
        ) {
            $book->updateBook(
                $bookId,
                $newBookImage,
                $bookName,
                $bookGenre,
                $bookAuthor,
                $bookEdition,
                $bookPublisher,
                $bookIsbn,
                $bookDes,
                $bookRating,
                $bookLanguage,
                $bookCondition,
                $ownerId
            );
        } else {
            $_SESSION['msg'] = "Please Fill the all field properly!";
            header('location:bookEdit.php');
        }
    }
    // for add favouraite
    if (isset($_POST['addfav'])) {
        $bookId = $_POST['book_id'];
        $userId = $_SESSION['user_id'];
        $book->insertFavourite($bookId, $userId);
    }

}
// delete book
if (isset($_GET['deletebookid'])) {
    $bookId = isset($_GET['deletebookid']) ? $_GET['deletebookid'] : null;
    if ($bookId !=null) {
        $book->deletePersonalBook($bookId, $_SESSION['user_id']);
    } else {
        $_SESSION['msg'] = "Please provide Book Id for Delete Book!";
        header('location:personalBook.php');
    }
}

// for resend code
if (isset($_GET['type']) && $_GET['type'] == 'resend') {
    $id =  $_SESSION['new_id'];
    $email = $_SESSION['new_email'];
    $user->insertRegisterOtp($id, $email);
}
// book request
if (isset($_GET['type']) && $_GET['type'] == 'bookrequest') {
    $bookId = $_POST['bookid'];
    $ownerId = $_POST['ownerid'];
    $requesterId = $_SESSION['user_id'];
    $book->bookRequest($bookId, $ownerId, $requesterId);
}
// book return request
if (isset($_GET['type']) && $_GET['type'] == 'bookreturnrequest') {
    $bookId = $_POST['bookid'];
    $ownerId = $_POST['ownerid'];
    $bookRating = $_POST['bookrating'];
    $bookReview = $_POST['review'];
    $requesterId = $_SESSION['user_id'];
    $reviewerName = $_SESSION['user_name'];
    $book->bookReturnRequest(
        $bookId,
        $ownerId,
        $requesterId,
        $bookRating,
        $bookReview,
        $reviewerName
    );
}

