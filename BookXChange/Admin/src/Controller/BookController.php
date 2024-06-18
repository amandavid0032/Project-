<?php
/**
 * Bookcontroller that controls all the book functionality
 *
 * PHP version 7.4.30
 *
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
namespace Book\Bookxchange\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Bookcontroller class that all the functions related to book
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

class BookController
{

    private $_loader;
    private $_twig;

    /**
     * Constructor for the bookController
     *
     * @param $book_m is the object for the bookModel
     */
    public function __construct($book_m)
    {

        $this->loader = new FilesystemLoader(__DIR__ . '/../view/templates');
        $this->twig = new Environment($this->loader);
        $this->book_m = $book_m;
    }

    /**
     * Function to get all the books and show in twig file
     *
     * @return returns to the twig file with the book list array
     */
    public function getBooks()
    {
        // global $admin_m;
        $b_list = $this->book_m->getBooksModel();
        // $b_list = json_encode($b_list);
        return $this->twig->render('book_list.html.twig', ['b_array' => $b_list]);
    }

    /**
     * Function to show the edit book form
     * 
     * @param $bookDetail array that contains the book details
     * 
     * @return returns the data to the twig file(edit_book_form.html.twig)
     */
    public function showBookEditForm($bookDetail)
    {
        return $this->twig->render(
            'edit_book_form.html.twig', ['bookDetial' => $bookDetail]
        );


    }

    /**
     * Function to block the book
     *
     * @param $id is id of the book which needs to be blocked
     *
     * @return Nothing to return in this function
     */
    public function blockBook(int $id)
    {
        // echo "blocking book";
        $blkBookRst = $this->book_m->blockBookModel($id);
        if ($blkBookRst) {
            $b_list = $this->book_m->getBooksModel();
            $bookListHtml = $this->twig->render(
                'book_list.html.twig',
                ['b_array' => $b_list]
            );
            $jsonresponse = [
                "html" => $bookListHtml,
            ];

            echo json_encode($jsonresponse);
            exit;
        }

    }

    /**
     * Function to unblock the book
     *
     * @param $id is id of the book which needs to be unblocked
     *
     * @return Nothing to return in this function
     */
    public function unBlockBook(int $id)
    {
        $unBlkBook = $this->book_m->unBlockBookModel($id);
        if ($unBlkBook) {
            $b_list = $this->book_m->getBooksModel();
            $bookListHtml = $this->twig->render(
                'book_list.html.twig', ['b_array' => $b_list]
            );
            $jsonresponse = [
                "html" => $bookListHtml,
            ];

            echo json_encode($jsonresponse);
            exit;
        }

    }

    /**
     * Function to delete the book
     *
     * @param $id is id of the book which needs to be deleted
     *
     * @return Nothing to return in this function
     */
    public function deleteBook(int $id)
    {

        $dltBook = $this->book_m->deleteBookModel($id);
        if ($dltBook) {
            $d_list = $this->book_m->getBooksModel();
            $bookListHtml = $this->twig->render(
                'book_list.html.twig', ['b_array' => $d_list]
            );
            $jsonresponse = [
                "html" => $bookListHtml,
            ];

            echo json_encode($jsonresponse);
            exit;
        }
    }

    /**
     * Function to show the book profile
     *
     * @param $id is id of the book which needs to be obeserved
     *
     * @return twig file with the details of teh book.
     */
    public function bookProfile(int $id)
    {
        // echo "inside book controller";
        $bookHistory = $this->book_m->bookHistory($id);
        $bookDetails = $this->book_m->bookProfileModel($id);
        $original_date = $bookDetails['upload_date'];
        // echo $original_date;

        // // Creating timestamp from given date
        $timestamp = strtotime($original_date);
        // // Creating new date format from that timestamp
        $upload_date = date("d-m-Y", $timestamp);

        return $this->twig->render(
            'book_profile.html.twig', ['bookDetails' => $bookDetails,
                'bookHistory' => $bookHistory, 'upload_date' => $upload_date]
        );

    }

    /**
     * Function get the details about the book for editing pupose
     *
     * @param $bookId is the id of the book whose details need to be displayed
     *                in the modal
     *
     * @return $bookDetail a array with the details of the book
     */
    public function getBookDetail(int $bookId) : array
    {
        $bookDetail = $this->book_m->getBookDetailModel($bookId);
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
    public function updateBookDetails(
        int $id, string $bookName, string $bookGenre,
        string $bookAuthor, int $bookEdition, string $bookDescription
    ) : bool {
        $updateBookDetail = $this->book_m->updateBookDetailsModel(
            $id, $bookName, $bookGenre, $bookAuthor,
            $bookEdition, $bookDescription
        );
        return true;
    }

}
