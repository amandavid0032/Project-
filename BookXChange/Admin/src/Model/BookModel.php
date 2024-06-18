<?php

/**
 * Bookmodela that contains all the query functions for the book
 *
 * PHP version 7.4.30
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Bookxchang_Application
 * @package    BookXchange
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
 * BookModel class that all the functions related to book
 *
 * PHP version 8.1.3
 *
 * @category   Bookxchange
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */

class BookModel
{
    /**
     * Constructor for the bookModel
     *
     * @param $conn is the object for the connection
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Function to all the books details
     *
     * @return returns the list of book in an array
     */
    public function getBooksModel() : array
    {
        $getBooksStmt = $this->conn->prepare(
            "SELECT b.* , r.user_name as ownerName
            FROM books as b
            INNER JOIN register as r ON b.owner_id = r.id"
        );

        $getBooksStmt->execute();

        $getBooksData = $getBooksStmt->get_result();

        $books_data = $getBooksData->fetch_all(MYSQLI_ASSOC);
        return $books_data;
    }

    /**
     * Function to block the required book
     *
     * @param $id is the id of the book which needs to be blocked
     *
     * @return returns a boolean value true after blocking the book
     */
    public function blockBookModel(int $id) : bool
    {
        $status = "blocked";
        $blkBookStmt = $this->conn->prepare(
            "update books set status = ? where id = ?"
        );
        $blkBookStmt->bind_param("si", $status, $id);
        $blkBookStmt->execute();

        return true;

    }

    /**
     * Function to Unblock the required book
     *
     * @param $id is the id of the book which needs to be unblocked
     *
     * @return returns a boolean value true after unblocking the book
     */
    public function unBlockBookModel(int $id) : bool
    {
        $status = "active";
        $unblkBookStmt = $this->conn->prepare(
            "update books set status = ? where id = ?"
        );
        $unblkBookStmt->bind_param("si", $status, $id);
        $unblkBookStmt->execute();
        return true;

    }

    /**
     * Function to Delete the required book
     *
     * @param $id is the id of the book which needs to be deleted
     *
     * @return returns a boolean value true after deleting the book
     */
    public function deleteBookModel(int $id) : bool
    {
        $dltBookStmt = $this->conn->prepare("delete from books where id = ?");
        $dltBookStmt->bind_param("i", $id);
        $dltBookStmt->execute();
        return true;
    }

    /**
     * Function get the details about the book
     *
     * @param $id is the id of the book whose profile needs to be observed
     *
     * @return returns a array with the details of the book
     */
    public function bookProfileModel(int $id) : array
    {
        $bookDetailStmt = $this->conn->prepare("select * from books where id = ?");
        $bookDetailStmt->bind_param("i", $id);
        $bookDetailStmt->execute();
        $bookResult = $bookDetailStmt->get_result();
        $bookDetail = $bookResult->fetch_assoc();

        return $bookDetail;
    }

    /**
     * Function get the history about the book
     *
     * @param $id is the id of the book whose history is required
     *
     * @return returns a array with the history of the book
     */
    public function bookHistory(int $id) : array
    {
        $bookHistoryStmt = $this->conn->prepare(
            "SELECT b.book_name,rg.user_name as requester, r.status,
             r.reason, r.rqst_date, r.issued_date, r.return_date
            FROM request as r
            INNER JOIN books as b ON b.id = r.book_id
            INNER JOIN register as rg ON rg.id = r.requester_id
            WHERE book_id = ?"
        );

        $bookHistoryStmt->bind_param("i", $id);
        $bookHistoryStmt->execute();
        $bookHistoryRst = $bookHistoryStmt->get_result();
        $result = $bookHistoryRst->fetch_all(MYSQLI_ASSOC);
        return $result;

    }

    /**
     * Function get the details about the book for editing pupose
     *
     * @param $bookId is the id of the book whose details need to be displayed
     *                in the modal
     *
     * @return $bookDetail a array with the details of the book
     */
    public function getBookDetailModel(int $bookId) : array
    {
        $bookDetailStmt = $this->conn->prepare("select * from books where id = ?");
        $bookDetailStmt->bind_param("i", $bookId);
        $bookDetailStmt->execute();
        $result = $bookDetailStmt->get_result();
        $bookDetail = $result->fetch_assoc();
        return $bookDetail;

    }

    /**
     * Function to update the book details obtained from the book modal
     *
     * @param $id              is the id of the book whose details need 
     *                         to be updated.
     * @param $bookName        a string value, obtained fromt the book modal.
     * @param $bookGenre       a string value, that holds the genre of the book.
     * @param $bookAuthor      a string value, that holda the author of the book.
     * @param $bookEdition     a int value, that holds a edition of the book.
     * @param $bookDescription a string value that holds the description
     *                         of the book given by the owner of the book.
     * @param $bookRating      a float value that holds the rating for the book.
     * 
     * @return returns boolean value true after updating the code.
     */
    public function updateBookDetailsModel(
        int $id, string $bookName, string $bookGenre, string $bookAuthor,
        int $bookEdition, string $bookDescription
    ) : bool {
        $updateBookStmt = $this->conn->prepare(
            "update books set book_name = ?,
            genre = ?, author = ?, edition = ?, description = ?
            where id = ?"
        );
        $updateBookStmt->bind_param(
            "sssisi", $bookName, $bookGenre, 
            $bookAuthor, $bookEdition, $bookDescription, $id
        );
        $updateBookStmt->execute();
        return true;

    }
}
