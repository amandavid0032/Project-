<?php
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middleware\AuthMiddleware;
use Slim\App;


$app->get('/users', 'UserHelper:userList');
$app->get('/tokenGen', 'tokenGen:genCSRFTkn');
$app->post('/signUp', 'UserHelper:signUp');
$app->post('/logIn', 'UserHelper:logIn');
$app->post('/bookList', 'BookHelper:bookList');
$app->get('/getLang/{user_id}', 'UserHelper:getLanguage');
$app->get('/getGenre/{user_id}', 'UserHelper:getGenre');
$app->post('/addUpdateUser/{userId}', 'UserHelper:addUpdate');
$app->post('/getOtp', 'UserHelper:getOtp');
$app->post('/verifyOtp', 'UserHelper:verifyOtp');

$app->group('', function() use ($app) {
    //End point related to user
    $app->get('/logOut/{userId}', 'UserHelper:logOut');
    $app->get('/getUserById/{userId}', 'UserHelper:getUserById');
    $app->post('/resetPass', 'UserHelper:resetPassword');
    $app->post('/submitGenre', 'UserHelper:submitGenre');   
    $app->post('/submitLang','UserHelper:submitLang');
    $app->get('/deleteProfilePicture/{userId}','UserHelper:deleteProfilePicture');
    $app->get('/delete_account/{userId}', 'UserHelper:deleteAccount');
    
    //End point related to books.
    $app->post('/getBookById/{bookId}', 'BookHelper:getBookById');
    $app->post('/addUpdateBook/{bookId}', 'BookHelper:addUpdate');
    $app->post('/updateBook/{bookId}', 'BookHelper:updateBook');
    $app->post('/deleteBook/{bookId}', 'BookHelper:deleteBook');
    $app->post('/addReview', 'BookHelper:addReview');
    $app->post('/editReview', 'BookHelper:editReview');
    $app->post('/getUserReview', 'BookHelper:getUserReview');
    $app->get('/reviewList/{bookId}', 'BookHelper:reviewList');
    $app->post('/deleteFeed', 'BookHelper:deleteFeed');
    $app->post('/personalBooks/{userId}', 'BookHelper:personalBooks');
    $app->post('/borrowHistory', 'BookHelper:borrowHistory');
    $app->post('/lendingHistory', 'BookHelper:lendingHistory');
    $app->post('/addRemoveFavourite/{user_id}', 'BookHelper:addRemoveFavourite');
    $app->get('/showWishlist/{user_id}', 'BookHelper:showWishlist');
    $app->get('/getBooksByGenre/{genre_name}', 'BookHelper:getBooksByGenre');
    $app->get('/findBooks/{bookQuery}','BookHelper:searchBook');
    $app->get('/getBookEditions/{bookName}', 'BookHelper:getBookEditions');
    $app->post('/getSharedUsers', 'BookHelper:getSharedUsers');
    $app->post('/searchBooks', 'BookHelper:searchBooks');
    
    //End point related to request
    $app->post('/requestStatus', 'RequestHelper:requestStatus');
    $app->get('/listReceivedRequest/{userId}', 'RequestHelper:listReceivedRequest');
    $app->get('/listSentRequest/{userId}', 'RequestHelper:listSentRequest');
    $app->post('/requestForBook', 'RequestHelper:requestForBook');//user can request for a book or cancell the request by himself.
    $app->post('/accept_reject_request', 'RequestHelper:accept_reject_request');
    $app->post('/notif_List', 'RequestHelper:notificationList');
    $app->post('/book_review', 'RequestHelper:book_review');
    $app->post('/returnBookByBorrower', 'RequestHelper:returnBookByBorrower');

});

$app->post('/insertRegisterOtp', 'UserHelper:insertRegisterOtp');  
$app->post('/getOtpVerify', 'UserHelper:getOtpVerify');
$app->post('/sendMsg', 'ChatHelper:sendMsg');
$app->post('/blockUserForBook',  'BookHelper:blockUserForBook');
$app->post('/unblockUserForBook',  'BookHelper:unblockUserForBook');
$app->post('/getBlockedBooks',  'BookHelper:getBlockedBooks');

// ->add(new AuthMiddleware());




