<?php
/**
 * Index page that controls login.
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

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Model\BookM;

/**
 * Book that controls Book section.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Book
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Constructor for the Book controller.
     *
     * @param $baseurl is the object for book controller.
     */
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $this->bookM = new BookM();
    }


    /**
     * Function getViewBook give all view books.
     *
     * @param $id      is book id.
     * @param $session is session
     *
     * @return static twig file with book details.
     */
    public function getViewBook(int $id, int $session)
    {
        $bookdetail = $this->bookM->getBookDetails($id);
        $bookFav = $this->bookM->getBookFav($id, $session);
        $allfeedback = $this->bookM->allBookFeedback($id);
        $isbn = $bookdetail['isbn'];

        $bookListByisbn = $this->bookM->getBookListByIsbn(
            $session,
            $isbn
        );
        return $this->_twig->render(
            'bookView.html.twig',
            ['session'=>$session,
            'bookdetail'=>$bookdetail,'allbookfeedback'=>$allfeedback,
             'bookListByIsbn'=>$bookListByisbn]
        );
    }

     /**
      * Function getBookDetails give personal book view.
      *
      * @param $id is book id.
      *
      * @return static twig file with book details.
      */
    public function getPersonalViewBook(int $id)
    {
        $bookdetail = $this->bookM->getBookDetails($id);

        return $this->_twig->render(
            'personalbookView.html.twig',
            ['bookdetail'=>$bookdetail]
        );

    }

    /**
     * Addbook function add the books
     *
     * @return static return twig file.
     */
    public function addBook()
    {
        $language = $this->bookM->getLanguage();
        $genre = $this->bookM->getGenre();

        return $this->_twig->render(
            'addBook.html.twig',
            ['language'=>$language, 'genre'=>$genre]
        );
    }

    /**
     * Function addNewbook adds new book.
     *
     * @param $bookImage     image for book.
     * @param $bookName      name for book.
     * @param $bookGenre     genre for book.
     * @param $bookAuthor    author name of book.
     * @param $bookEdition   edition of book.
     * @param $bookPublisher is book publisher.
     * @param $bookIsbn      is book isbn.
     * @param $bookDes       description of book.
     * @param $bookRating    book rating.
     * @param $bookLang      book language
     * @param $bookCon       book condition.
     * @param $ownerId       book owner id.
     *
     * @return int return id of recently added book.
     */
    public function addNewBook(
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookPublisher,
        string $bookIsbn,
        string $bookDes,
        int $bookLang,
        int $bookCon,
        float $bookRating,
        int $ownerId
    ): int {
        $bookId = null;
        $addNewBook = $this->bookM->addNewBook(
            $bookId,
            $bookImage,
            $bookName,
            $bookGenre,
            $bookAuthor,
            $bookEdition,
            $bookPublisher,
            $bookIsbn,
            $bookDes,
            $bookRating,
            $bookLang,
            $bookCon,
            $ownerId
        );
        return $addNewBook;
    }
    /**
     * Fuction getReviews give review of books
     *
     * @param $bookId is book id
     *
     * @return static twig file
     */
    public function getReviews(int $bookId)
    {
        $feedback = $this->bookM->bookFeedback($bookId);
        $allfeedback = $this->bookM->allBookFeedback($bookId);
        return $this->_twig->render(
            'bookReviews.html.twig',
            ['bookfeedback'=>$feedback,'allbookfeedback'=>$allfeedback]
        );
    }

    /**
     * Function bookRequest for request book.
     *
     * @param $bookId      is book id.
     * @param $ownerId     is book ownerId.
     * @param $requesterId is  id  of requester.
     *
     * @return void array in json format.
     */
    public function bookRequest(int $bookId, int $ownerId, int $requesterId): void
    {
        $request = $this->bookM->bookRequest($bookId, $ownerId, $requesterId);
        $res = ['request'=>$request];
        echo json_encode($res);
    }

    /**
     * Function insertBookFeedback for feedback
     *
     * @param $bookId   is book id.
     * @param $feedback is feedback.
     * @param $userid   is user id.
     *
     * @return bool  array in json format.
     */
    public function insertBookFeedback(
        int $bookId,
        string $feedback,
        int $userid
    ): bool {
        $feedback = $this->bookM->insertBookFeedback(
            $bookId,
            $feedback,
            $userid
        );
        if ($feedback == true) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function bookReturnRequest for issued book.
     *
     * @param $bookId       is book id.
     * @param $ownerId      is book ownerId.
     * @param $requesterId  is  id  of requester.
     * @param $bookRating   is book rating.
     * @param $bookReview   is book review.
     * @param $bookReviewer is book Reviewer
     *
     * @return void array in json format.
     */
    public function bookReturnRequest(
        int $bookId,
        int $ownerId,
        int $requesterId,
        float $bookRating,
        string $bookReview,
        string $bookReviewer
    ) {
        $this->bookM->updateBookRating($bookId, $bookRating);
        $review = $this->bookM->insertBookFeedback(
            $bookId,
            $bookReview,
            $requesterId,
            $bookReviewer
        );
        $request = $this->bookM->bookReturnRequest($bookId, $ownerId, $requesterId);
        $bookStatus = $this->bookM->updateBookStatus($bookId, 2);
        $res = ['returnrequest'=>$request];
        echo json_encode($res);
    }

    /**
     * Function getPersonalBook give the personalbooks.
     *
     * @param $id id of owner.
     *
     * @return static
     */
    public function getPersonalBook(int $id)
    {
        $personalBooks = $this->bookM->getPersonalBook($id);
        return $this->_twig->render(
            'personalbook.html.twig',
            ['personalBooks'=>$personalBooks]
        );
    }

    /**
     * Function deletePersonalBook delete personal book.
     *
     * @param $bookId  is book id.
     * @param $ownerId is owner id.
     *
     * @return void array in json format.
     */
    public function deletePersonalBook(int $bookId, int $ownerId)
    {
        $bookDetails = $this->bookM->getBookDetails($bookId);
        $deleteBook = $this->bookM->deletePersonalBook($bookId);
        if ($deleteBook === true) {
            $_SESSION['msg'] = "You have Deleted ".$bookDetails['book_name'];
            header('location:personalBook.php');
        } else {
            $_SESSION['msg'] = "Deletion Book Failed !!";
            header('location:personalBook.php');
        }
    }

    /**
     * Function getAllSentRequest to get all request book.
     *
     * @param $userId is user id.
     *
     * @return static twig file sentrequest with all list.
     */
    public function getAllSentRequest(int $userId)
    {
        $allSentRequest = $this->bookM->getAllSentRequest($userId);
        return $this->_twig->render(
            'sentrequest.html.twig',
            ['allsentrequest'=>$allSentRequest]
        );
    }

    /**
     * Function getAllRecievedRequest to get allreceived request book.
     *
     * @param $userId is user id.
     *
     * @return static twig file  with all received request list.
     */
    public function allReceivedRequest(int $userId)
    {
        $allSentRequest = $this->bookM->getAllSentRequest($userId);
        $allReceivedRequest = $this->bookM->getAllReceivedRequest($userId);
        return $this->_twig->render(
            'receivedrequest.html.twig',
            ['allreceivedrequest'=>
            $allReceivedRequest,
            'allsentrequest'=>$allSentRequest]
        );
    }

    /**
     * Function updateRequest update request status.
     *
     * @param $requesterId is id of requester.
     * @param $bookId      is book id.
     * @param $ownerId     is book owner id.
     * @param $status      is status of book.
     * @param $reason      is reason for reject book.
     *
     * @return void nothing return.
     */
    public function updateRequest(
        int $requesterId,
        int $bookId,
        int $ownerId,
        int $status,
        string $reason
    ): void {
        if ($status == 1) {
            $bookDetail = $this->bookM->getBookDetails($bookId);
            $bookStatus = $bookDetail['book_status'];
            if ($bookStatus == 0) {
                $request = $this->bookM->updateRequest(
                    $requesterId,
                    $bookId,
                    $ownerId,
                    $status,
                    $reason
                );
                if ($request == true) {
                    $bookStatus = $this->bookM->updateBookStatus($bookId, 1);
                    if ($bookStatus == true) {
                        $_SESSION['msg'] = 'Request Granted';
                    } else {
                        $_SESSION['msg'] = 'Error while updating book status';
                    }
                } else {
                    $_SESSION['msg'] = "Request not Granted";
                }
            } else {
                $_SESSION['msg'] = "You have already issued for other.
                 Let him/she return or cancel request!";
            }
        }
        if ($status == 4) {
            $request = $this->bookM->updateRequest(
                $requesterId,
                $bookId,
                $ownerId,
                $status,
                $reason
            );
            if ($request == true) {
                $_SESSION['msg'] = 'Request is Rejected';
            } else {
                $_SESSION['msg'] = "Request isnot Rejected";
            }
        }
        if ($status == 3) {
            $request = $this->bookM->updateRequest(
                $requesterId,
                $bookId,
                $ownerId,
                $status,
                $reason
            );
            if ($request == true) {
                $bookStatus = $this->bookM->updateBookStatus($bookId, 0);
                if ($bookStatus == true) {
                    $_SESSION['msg'] = 'Return Request Granted';
                } else {
                    $_SESSION['msg'] = 'Error while updating book status';
                }
            } else {
                $_SESSION['msg'] = "Return Request not Granted";
            }
        }
        header('location:response.php');
    }

    /**
     * Function getBookDetails give books detail.
     *
     * @param $id is book id.
     *
     * @return void array in json form.
     */
    public function getBookDetails(int $id)
    {
        $personalBooks = $this->bookM->getBookDetails($id);
        echo json_encode($personalBooks);
    }


    /**
     * Function getBookId
     *
     * @param $bookId is book id
     *
     * @return static twig file
     */
    public function getBookEdit(int $bookId)
    {
        $language = $this->bookM->getLanguage();
        $genre = $this->bookM->getGenre();
        $bookDetail = $this->bookM->getBookDetails($bookId);
        return $this->_twig->render(
            'bookedit.html.twig',
            ['bookDetail'=>$bookDetail,
            'language'=>$language,
             'genre'=>$genre]
        );
    }
    /**
     * Function getSearchBook
     *
     * @param $bookName   name of book
     * @param $bookCat    is catergory of book
     * @param $bookAuthor is book Author list
     *
     * @return static twig file
     */
    public function getSearchBook(
        string $bookName,
        string  $bookCat,
        string $bookAuthor
    ) {
        $genreAndAuthorList = $this->bookM->getPresentGenreAndAuthorList();
        $searchBookList = $this->bookM->getSearchBookList(
            $bookName,
            $bookCat, $bookAuthor
        );
        return $this->_twig->render(
            'searchBookList.html.twig',
            ['searchBookList'=>$searchBookList,
            'genreAndAuthorList'=>$genreAndAuthorList]
        );
    }

    /**
     * Function updateBook update the books.
     *
     * @param $bookId        is id for book.
     * @param $bookImage     image of book.
     * @param $bookName      is name of book.
     * @param $bookGenre     is Genre of book.
     * @param $bookAuthor    is author of book.
     * @param $bookEdition   is edition of book.
     * @param $bookPublisher is publisher of book.
     * @param $bookIsbn      is book isbn
     * @param $bookDes       is description of book.
     * @param $bookRating    is rating of book.
     * @param $booklanguage is book language.
     * @param $bookCondition is book condition.
     * @param $ownerId       is owner id.
     *
     * @return void json encodeed array.
     */
    public function updateBook(
        int $bookId,
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookPublisher,
        string $bookIsbn,
        string $bookDes,
        float $bookRating,
        int $bookLanguage,
        int $bookCondition,
        int $ownerId
    ) {
        $addNewBook = $this->bookM->addNewBook(
            $bookId,
            $bookImage,
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
        if ($addNewBook == true) {
            $_SESSION['msg'] = "You edited $bookName!";
            header('location:personalBook.php');
        } else {
            $_SESSION['msg'] = "There is no any changes!";
            header('location:personalBook.php');
        }

    }
    /**
     * Function getLendingHistory
     * 
     * @param $userId is user id
     * 
     * @return static is list of array
     */
    public function getLendingHistory(int $userId)
    {
        $lendingHistoryList = $this->bookM->getLendingHistoryList($userId);
        return $this->_twig->render('lendingHistory.html.twig', ['lendingHistoryList'=>$lendingHistoryList]);
    }

    /**
     * Function getBorrows
     * 
     * @param $userId is user id
     * 
     * @return static is list of array
     */
    public function getBorrows(int $userId)
    {
        $borrowsList = $this->bookM->getBorrowsList($userId);
        return $this->_twig->render('borrows.html.twig', ['borrowsList'=>$borrowsList]);
    }

    /**
     * Function getWishList 
     * 
     * @param $userId is user id.
     * 
     * @return static twig file of wishlist
     */
    public function getWishList( int $userId)
    {
        $wishList = $this->bookM->getWishList($userId);
        return $this->_twig->render('wishList.html.twig', ['wishList'=>$wishList]);
    }
    /**
     * Function insertFavourite
     * 
     * @param $bookId is book id
     * @param $userId is user id
     * 
     * @return void nothing
     */
    public function insertFavourite(int $bookId, int $userId):void
    {
        $insertFavourite = $this->bookM->insertFavourite($bookId, $userId);
        if ($insertFavourite == true) {
            $_SESSION['msg'] = "Added successfully";
        } else {
            $_SESSION['msg'] = "Failed";
        }
        header('location:bookView.php?bookid='.$bookId);
    }
    }
}
