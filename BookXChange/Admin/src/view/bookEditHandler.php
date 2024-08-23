<?php
/**
 * This page is called when the action against the book is required.
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
require '../../vendor/autoload.php';
require '../config/db.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Book\Bookxchange\Model\BookModel;


$book_m = new BookModel($conn);
$book = new \Book\Bookxchange\Controller\BookController($book_m);
$loader = new FilesystemLoader(__DIR__ . '/../view/templates');
$twig = new Environment($loader);

//code to get book details on the modal to edit
if (isset($_POST['bookID']) && $_POST['bookID'] != "") {
    $bookId = intval($_POST['bookID']);
    $bookDetail = $book->getBookDetail($bookId);
    // echo json_encode($bookDetail);
    // $booForm = $book->showBookEditForm($bookDetail);
    $bookDetailHtml = $twig->render(
        'edit_book_form.html.twig', [
            'bookDetail' => $bookDetail,
            'bookId' => $bookId
        ]
    );
    $jsonresponse = [
        "html" => $bookDetailHtml
    ];
    echo json_encode($jsonresponse);
    exit;


}

//code to update bookDetail that are filled in modal
if (isset($_POST['BookId'])) {
    $id = intval($_POST['BookId']);
    $bookName = $_POST['bookName'];
    $bookGenre = $_POST['bookGenre'];
    $bookAuthor = $_POST['bookAuthor'];
    $bookEdition = $_POST['bookEdition'];
    $bookDescription = $_POST['bookDescription'];
    // $bookRating = $_POST['bookRating'];

    $updateBook = $book->updateBookDetails(
        $id, $bookName, $bookGenre, $bookAuthor,
        $bookEdition, $bookDescription
    );
    if ($updateBook) {
        $b_list = $book_m->getBooksModel();
        $bookListHtml = $twig->render('book_list.html.twig', ['b_array' => $b_list]);
        $jsonresponse = [
            "html" => $bookListHtml
        ];

        echo json_encode($jsonresponse);
        exit;
    }

    
}



?>