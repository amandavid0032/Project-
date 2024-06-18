<?php

/**
 * Book Model.
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
 * BookM that controls book database.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class BookM
{
    private $_conn;

    /**
     * Constructor for the Book Model.
     */
    public function __construct()
    {
        $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }
    /**
     * Give the user choosen language 
     * 
     * @param $sesion user id 
     * 
     * @return array list of ids
     */
    public function userLang(int $session): array
    {
        $langArr = [];
        $sql = "SELECT * FROM user_lang WHERE `user_id` = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $session);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $r = $row['lang_id'];
            array_push($langArr, $r);
        }
        $stmt->close();
        return $langArr;
    }

    /**
     * Give the user choosen Genre 
     * 
     * @param $sesion user id 
     * 
     * @return array list of ids
     */
    public function userGenre(int $session): array
    {
        $genreArr = [];
        $sql = "SELECT * FROM user_genre
         WHERE `user_id` = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $session);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $r = $row['genre_id'];
            array_push($genreArr, $r);
        }
        $stmt->close();
        return $genreArr;
    }

    /**
     * Function getRecentBook give recent books.
     *
     * @param $session is sesssion id
     * 
     * @return array array of recent book.
     */
    public function getRecentBook(int $session)
    {
        $start = 10;
        if ($session != 0) {
            $lang = $this->userLang($session);
            $langIds = implode(",", $lang);
            $sql = "SELECT b.*, g.genre as ggenre FROM `books` as b
            INNER JOIN genre as g on g.id = b.genre 
            WHERE b.book_lang IN ($langIds)
            ORDER BY b.upload_date DESC LIMIT ?";
        } else {
            $sql = "SELECT b.*, g.genre as ggenre FROM `books` as b
            INNER JOIN genre as g on g.id = b.genre 
            ORDER BY b.upload_date DESC LIMIT ?";
        }
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $start);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getBookDetails give books detail.
     *
     * @param $id is book id.
     *
     * @return array array of bookdetail.
     */
    public function getBookDetails(int $id): array
    {
        $sql = "SELECT * FROM `books` WHERE id=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr = $res->fetch_assoc();
        $stmt->close();
        return $arr;
    }
    /**
     * Function getGenre
     *
     * @return array array of genre.
     */
    public function getGenre(): array
    {
        $sql = "SELECT id, `genre` FROM `genre`";
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('no genre list');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getPresentGenreAndAuthorList
     *
     * @return array array of genre.
     */
    public function getPresentGenreAndAuthorList(): array
    {
        $sql = "SELECT author, `genre` FROM `books`";
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('no genre list');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }
    /**
     * Function getlanguage
     *
     * @return array array of language.
     */
    public function getLanguage(): array
    {
        $sql = "SELECT id, `name` FROM `language`";
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('no language list');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getMostPopularBook
     *
     * @param $session user session
     * 
     * @return array array of popular book.
     */
    public function getMostPopularBook(int $session)
    {
        if ($session != 0) {
            $lang = $this->userLang($session);
            $langIds = implode(",", $lang);
            $sql = "SELECT *,count(r.book_id) as bookc FROM books 
            INNER JOIN (SELECT book_id FROM request) as r
            on r.book_id = id
            WHERE book_lang IN ($langIds) 
            GROUP BY book_id 
            ORDER BY count(*) DESC, book_id";
        } else {
            $sql = "SELECT *,count(r.book_id) as bookc FROM books 
            INNER JOIN (SELECT book_id FROM request) as r
            on r.book_id = id  
            GROUP BY book_id 
            ORDER BY count(*) DESC, book_id";
        }
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();

        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getMostUniqueGenre
     *
     * @param $session is user session
     *
     * @return array array of popular Genre.
     */
    public function getMostUniqueGenre(int $session)
    {
        $status = 'active';
        if ($session != 0) {
            $genre = $this->userGenre($session);
            $genreIds = implode(",", $genre);
            $sql = "SELECT b.genre,count(*) as bookc ,g.genre as ggenre
            FROM books as b
            INNER JOIN `genre` as g on g.id = b.genre
            WHERE b.status = ? and b.genre IN ($genreIds)
            GROUP BY b.genre 
            ORDER BY count(*) DESC ";
        } else {
            $sql = "SELECT b.genre,count(*) as bookc ,g.genre as ggenre
            FROM books as b
            INNER JOIN `genre` as g on g.id = b.genre
            WHERE b.status = ?
            GROUP BY b.genre 
            ORDER BY count(*) DESC ";
        }
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
        
    }
    /**
     * Function addNewbook adds new book.
     *
     * @param $bookId        book id.
     * @param $bookImage     image for book.
     * @param $bookName      name for book.
     * @param $bookGenre     genre for book.
     * @param $bookAuthor    author name of book.
     * @param $bookEdition   edition of book.
     * @param $bookPublisher publisher of book.
     * @param $bookIsbn      book isbn number.
     * @param $bookDes       description of book.
     * @param $bookRating    book rating.
     * @param $bookLang      book language
     * @param $bookCon       book condition.
     * @param $ownerId       book owner id.
     *
     * @return int return last index id or 0.
     */
    public function addNewBook(
        int $bookId = null,
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookPublisher,
        string $bookIsbn,
        string $bookDes,
        float $bookRating,
        int $bookLang,
        int $bookCon,
        int $ownerId
    ): int {
        $sql = "INSERT INTO `books` (id,image, book_name,
         genre, author,edition,publisher,isbn,
          description, rating,book_lang,book_condition, owner_id) 
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
          ON DUPLICATE KEY UPDATE
           id=VALUES(id),image=VALUES(image),book_name=VALUES(book_name),
           genre=VALUES(genre),author=VALUES(author),
           edition=VALUES(edition),publisher=VALUES(publisher),
           isbn=VALUES(isbn),
           description=VALUES(description),
           rating=VALUES(rating),
           book_lang=VALUES(book_lang),
           book_condition=VALUES(book_condition),
           owner_id=VALUES(owner_id) ";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "issssssssdiii",
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
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = $this->_conn->insert_id;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function getAllBookList
     *
     * @return array array of all book.
     */
    public function getAllBookList()
    {
        $status = 'active';
        $sql = "SELECT b.*,g.genre as ggenre FROM `books` as b
         LEFT JOIN genre as g ON g.id = b.genre
         WHERE b.status = ? 
         ORDER BY upload_date DESC";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function bookFeedback give feedback for book.
     *
     * @param $bookid is book id.
     *
     * @return array array of bookfeedback.
     */
    public function bookFeedback(int $bookid): array
    {
        $sql = "SELECT b.id,b.book_name,b.author,b.description,
        b.image,b.owner_id, r.user_name, b.rating,
         r.image as user_image,r.mobile_no, r.address, r.email
        FROM books as b
        INNER JOIN register as r on r.id = b.owner_id
        WHERE b.id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book details present');
        }
        $arr = $res->fetch_assoc();
        $stmt->close();
        return $arr;
    }

    /**
     * Function allBookFeedback
     *
     * @param $bookId is bookid.
     *
     * @return array all feed back
     */
    public function allBookFeedback(int $bookId): array
    {
        $sql = "SELECT f.*,r.image as userimage, r.user_name as user_name 
        FROM feedback as f 
        INNER JOIN register as r on f.user_id = r.id 
        WHERE book_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function insertBookFeedback for feedback
     *
     * @param $bookId   is book id.
     * @param $feedback is feedback.
     * @param $userid   is user id.
     *
     * @return bool  true or false.
     */
    public function insertBookFeedback(
        int $bookId,
        string $feedback,
        int $userid
    ): bool {
        $sql = "INSERT INTO feedback (feedback, user_id, book_id)
         VALUES (?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("sii", $feedback, $userid, $bookId);
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
     * Function getBookListByIsbn
     *
     * @param $requesterId is requester id.
     * @param $isbn        is isbn
     *
     * @return array is list of books by isbn
     */
    public function getBookListByIsbn(int $requesterId, string $isbn): array
    {
        $sql = "SELECT b.*,rg.user_name as owner_name,
         rg.address as owner_address, r.status as reqst_status,
         r.requester_id as requester_id 
        FROM `books` as b
        LEFT OUTER JOIN `request` as r 
        on r.book_id = b.id and r.requester_id = ?
        INNER JOIN `register` as rg 
        on rg.id = b.owner_id
         WHERE b.isbn = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("is", $requesterId, $isbn);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }
    /**
     * Function getBookStatusByRequester
     *
     * @param $requesterId is requesterid
     * @param $isbn        is isbn
     *
     * @return array is list of books by isbn
     */
    public function getBookStatusByRequester(int $requesterId, string $isbn): array
    {
        // $sql = "SELECT * FROM `books` WHERE isbn=?";
        $sql = "SELECT b.*,r.status as rstatus,r.requester_id as requester_id
         FROM `books` as b
        LEFT OUTER JOIN `request` as r on r.book_id = b.id
         WHERE b.isbn = ? and r.requester_id = ? ";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("si", $isbn, $requesterId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }
    /**
     * Function bookRequest
     *
     * @param $bookId      is book id.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return bool true or false
     */
    public function bookRequest(int $bookId, int $ownerId, int $requesterId): bool
    {
        $rqst_date = date("Y-m-d h:i:sa");
        $issued_date =  '';
        $return_date = '';
        $status = 0;
        $reason = '';
        $isPresentId = $this->isPresentId($bookId, $ownerId, $requesterId);
        $presentId = isset($isPresentId['id']) ? $isPresentId['id'] : null;
        $sql = "INSERT INTO `request` 
        (id,requester_id,owner_id,book_id,status,
        reason,rqst_date,issued_date,return_date)
         VALUES (?,?,?,?,?,?,?,?,?)
         ON DUPLICATE KEY UPDATE id=VALUES(id),requester_id=VALUES(requester_id),
         owner_id=VALUES(owner_id), book_id=VALUES(book_id),
          status=VALUES(status), reason=VALUES(reason),
           rqst_date=VALUES(rqst_date), issued_date=VALUES(issued_date),
            return_date=VALUES(return_date)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "iiiiissss",
            $presentId,
            $requesterId,
            $ownerId,
            $bookId,
            $status,
            $reason,
            $rqst_date,
            $issued_date,
            $return_date
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
     * Function isPresentId
     *
     * @param $bookId      is book id.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return static id of request id
     */
    public function isPresentId(int $bookId, int $ownerId, int $requesterId)
    {
        $sql = "SELECT id FROM `request` WHERE
         book_id = ? AND owner_id = ? AND requester_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("iii", $bookId, $ownerId, $requesterId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        return $arr;
    }
    /**
     * Function getPersonalBook give the personalbooks.
     *
     * @param $id id of owner.
     *
     * @return static  list of book
     */
    public function getPersonalBook(int $id)
    {
        $sql = "SELECT * FROM `books`
         WHERE owner_id=? 
         ORDER BY upload_date DESC";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = null;
        } else {
            $arr = $res->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
        return $arr;
    }

    /**
     * Function getBookRating
     *
     * @param $bookId is book id.
     *
     * @return array is array of rating and rater.
     */
    public function getBookRating(int $bookId): array
    {
        $sql = "SELECT rating, rater
         FROM `books`
          WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        $stmt->close();
        return $arr;
    }
    /**
     * Function updateBookRating
     *
     * @param $bookId     is book id.
     * @param $bookRating is book rating.
     *
     * @return void nothing
     */
    public function updateBookRating(int $bookId, float $bookRating): void
    {
        $sql = "UPDATE `books` 
        SET rating = ? 
        WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("di", $bookRating, $bookId);
        $stmt->execute();
    }

    /**
     * Function bookReturnRequest
     *
     * @param $bookId      is bookid.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return bool true or false.
     */
    public function bookReturnRequest(
        int $bookId,
        int $ownerId,
        int $requesterId
    ): bool {
        $status = 2;
        $returnDate = date("Y-m-d h:i:sa");
        $sql = "UPDATE `request` SET status = ?, return_date = ?
         WHERE requester_id = ? 
         AND book_id = ?
          AND owner_id= ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "isiii",
            $status,
            $returnDate,
            $requesterId,
            $bookId,
            $ownerId
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
     * Function updateBookStatus
     *
     * @param $bookId is book id
     * @param $status is book status
     *
     * @return bool return true or false
     */
    public function updateBookStatus(int $bookId, int $status): bool
    {
        $sql = "UPDATE `books` SET book_status = ?
         WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ii", $status, $bookId);
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
     * Function deletePersonalBook delete personal book.
     *
     * @param $bookId is book id.
     *
     * @return bool true or false.
     */
    public function deletePersonalBook(int $bookId): bool
    {
        $sql = "DELETE FROM `books` WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function requestStatus status of request book
     *
     * @param $bookid is book id.
     * @param $userId is userId.
     *
     * @return mixed array of status
     */
    public function requestStatus(int $bookid, int $userId): mixed
    {
        $sql = "SELECT status FROM `request`
         WHERE book_id = ? and requester_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ii", $bookid, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        return $arr;
    }



    /**
     * Function  getAllSentRequest
     *
     * @param $userId is user id.
     *
     * @return array array of all sent request.
     */
    public function getAllSentRequest(int $userId): array
    {
        $sql = "SELECT r.status, r.reason,b.book_name,rg.user_name as owner_name
         FROM `request` as r
        INNER JOIN `books` as b on b.id=r.book_id
        INNER JOIN `register` as rg on rg.id=r.owner_id
         WHERE requester_id =?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr = $res->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
        return $arr;
    }

    /**
     * Function  getAllReceivedRequest
     *
     * @param $userId is user id.
     *
     * @return array array of all received request.
     */
    public function getAllReceivedRequest(int $userId): array
    {
        $sql = "SELECT r.status,r.requester_id,r.book_id,r.owner_id,
        b.book_name,rg.user_name as requester_name 
        FROM `request` as r
        INNER JOIN `books` as b on b.id=r.book_id
        INNER JOIN `register` as rg on rg.id=r.requester_id
         WHERE r.owner_id =?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr = $res->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
        return $arr;
    }

    /**
     * Function updateRequest
     *
     * @param $requesterId requster id
     * @param $bookId      is book id
     * @param $ownerId     is owner id.
     * @param $status      is status.
     * @param $reason      is reason for book.
     *
     * @return bool  true or false.
     */
    public function updateRequest(
        int $requesterId,
        int $bookId,
        int $ownerId,
        int $status,
        string $reason
    ): bool {
        $issuedDate = '';
        if ($status == 1) {
            $issuedDate = date("Y-m-d h:i:sa");
        }
        $sql = "UPDATE `request` SET status = ?, reason = ?, issued_date=?
         WHERE requester_id = ? AND book_id = ? AND owner_id= ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "issiii",
            $status,
            $reason,
            $issuedDate,
            $requesterId,
            $bookId,
            $ownerId
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
     * Function bookSeach
     *
     * @param $bookName   is book data to search
     * @param $bookCat    is book category
     * @param $bookAuthor is bookAuthor
     *
     * @return array array of matched records
     */
    public function getSearchBookList($bookName, $bookCat, $bookAuthor): array
    {
        if ($bookName != null) {
            $bookNameData = "%" . $bookName . "%";
        } else {
            $bookNameData = null;
        }
        if ($bookCat != null) {
            $bookCatData = "%" . $bookCat . "%";
        } else {
            $bookCatData = null;
        }
        if ($bookAuthor != null) {
            $bookAuthorData = "%" . $bookAuthor . "%";
        } else {
            $bookAuthorData = null;
        }

        $sql = "SELECT * FROM `books` 
        WHERE book_name LIKE ? OR author LIKE ? OR genre LIKE ? 
        ORDER BY upload_date DESC";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("sss", $bookNameData, $bookAuthorData, $bookCatData);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr = $res->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
        return $arr;
    }
    /**
     * Function getBorrowList
     *
     * @param $userId is user id
     *
     * @return array list of borrows
     */
    public function getBorrowsList(int $userId): array
    {
        $sql = "SELECT b.*, r.status as rstatus, r.rqst_date, r.issued_date, r.return_date, rg.user_name FROM request as r
         INNER JOIN books as b on b.id = r.book_id
         INNER JOIN register as rg on rg.id = r.requester_id
          WHERE r.requester_id = ? ORDER BY r.issued_date";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr =$res->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();

        return $arr;
    }
    /**
     * Function getLendingHistoryList
     *
     * @param $userId is user id
     *
     * @return array list of lending history
     */
    public function getLendingHistoryList(int $userId): array
    {
        $sql = "SELECT b.*, r.status as rstatus, r.rqst_date, r.issued_date,r.return_date, rg.user_name FROM request as r
         INNER JOIN books as b on b.id = r.book_id
         INNER JOIN register as rg on rg.id = r.requester_id
          WHERE r.owner_id = ? ORDER BY r.issued_date";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr =$res->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();

        return $arr;
    }

    /**
     * Function function getWishList
     *
     * @param $userId is user Id.
     *
     * @return array is list of array
     */
    public function getWishList(int $userId): array
    {
        $sql = "SELECT b.*, r.status as rstatus
        FROM favourite as f
        INNER JOIN books as b on f.book_id = b.id
        LEFT JOIN request as r on r.book_id = f.book_id
        WHERE f.user_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = [];
        } else {
            $arr =$res->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
        return $arr;
    }

     /**
     * Function insertFavourite
     * 
     * @param $bookId is book id
     * @param $userId is user id
     * 
     * @return bool true or false
     */
    public function insertFavourite(int $bookId, int $userId):bool
    {
        $sql = "INSERT INTO favourite (user_id,book_id) VALUES (? , ?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $bookId);
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
     * Function getBookFav
     * 
     * @param $id is bookId
     * @param $session is user session
     * 
     * @return bool true or false
     */
    public function getBookFav(int $id, int $session):bool
    {
        $sql = "SELECT * FROM favourite WHERE book_id = ? and user_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ii", $id, $session);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $arr = false;
        } else {
            $arr = true;
        }
        $stmt->close();
        return $arr;
    }
}
?>