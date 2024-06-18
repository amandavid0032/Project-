<?php
namespace App\Controllers;
// session_start();
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\GetTokenFromDb\GetToken;
use App\Token\GenToken;
use App\Config\Db;


class BookController
{
    protected $conn;
    protected $bookModelObj;
    protected $valToken;
    protected $token;
    public const SHOW_RECENT_ADDED_BOOKS = 10;

    public function __construct($bookModelObj, $conn)
    {
        $this->conn = $conn;
        $this->bookModelObj = $bookModelObj;
        $this->valToken = new GetToken($conn);
        $this->token = new GenToken();
    }

    public function bookList(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $search = trim($params['search'] ?? '');
        $userId = (int)trim($params['user_id'] ?? '');
        $noOfBooksToShowInRecentAdded = BookController::SHOW_RECENT_ADDED_BOOKS;

        if ($search == '') {
            $userLangIds = $this->bookModelObj->getUserLangIds($userId);
            $userGenreIds = $this->bookModelObj->getUserGenreIds($userId);

            if (!empty($userLangIds) && !empty($userGenreIds)) {
                $booksWithGenreAndLanguage = $this->bookModelObj->getBooksWithGenreAndLang($userLangIds, $userGenreIds) ?? [];
            } else {
                $booksWithGenreAndLanguage = [];
            }

            $booksWithLang = $this->bookModelObj->getBooksWithLang($userLangIds) ?? [];
            $latestUploadBookByDate = $this->bookModelObj->getLatestUploadBooks() ?? [];
            $recentBooks = array_merge($booksWithGenreAndLanguage, $booksWithLang, $latestUploadBookByDate);
            $popularBooks = $this->bookModelObj->getPopularBooks($userLangIds) ?? [];
            $userGenreBooks = $this->bookModelObj->getUserGenreBooks($userId) ?? [];

            $bookLists = $this->bookModelObj->listAllBooks(
                array_slice($recentBooks, 0, $noOfBooksToShowInRecentAdded),
                $popularBooks,
                $userGenreBooks
            );

            if ($bookLists) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "List of books",
                    "book" => $bookLists
                );
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Something went wrong.",
                    "book" => []
                );
            }
        } else {
            $searchRst = $this->bookModelObj->searchBook($search);
            if (count($searchRst) > 0) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "List of books.",
                    "book" => $searchRst
                );
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "No books found.",
                    "book" => []
                );
            }
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }



    public function addUpdate(Request $request, Response $response, $args)
    {
        $bookId = (int)$args['bookId'];
        $params = $request->getParsedBody();   
        $bName = trim($params['name'] ?? '');
        $bGenre = trim($params['genre'] ?? '');
        $bAuthor = trim($params['author'] ?? '');
        $edition = (int) trim($params['edition'] ?? '');
        $publisher = trim($params['publisher'] ?? '');
        $rating = trim($params['rating'] ?? '');
        $description = trim($params['description'] ?? '');
        $ISBN = trim($params['isbn'] ?? '');
        $user_id = trim($params['userid'] ?? '');
        $bookCondition = (int)trim($params['condition'] ?? '');
        $bookLang = trim($params['language'] ?? '');
        $review = trim($params['review'] ?? '');
        $noOfBooks = trim($params['noOfBooks'] ?? '');
        $bookImgLink = "0";

        if ($bookId == 0) {
            if (isset($_FILES['image']) && strlen($_FILES['image']['name']) != 0) {
                $allowedExt = ['png', 'jpg', 'jpeg'];
                $path = $_FILES['image']['name'];
                $imgExt = pathinfo($path, PATHINFO_EXTENSION);
                if (in_array($imgExt, $allowedExt)) {
                    $bImage = $_FILES['image'];
                    $img_name = $bImage['name'];
                    $img_path = $bImage['tmp_name'];
                    $bookDest = __DIR__."/../img/books/".$img_name;
                    $bookImgLink = "app/img/books/".$img_name;
                    move_uploaded_file($img_path, $bookDest);
                    //check if book Genre exists or not. If not then insert new genre coming from scanned barcode.
                    if (!is_numeric($bGenre)) {
                        $checkGenreExist = $this->bookModelObj->checkGenreExist($bGenre);
                        if ($checkGenreExist) {
                            $bGenre = $this->bookModelObj->getGenerId($bGenre);
                        } else {
                            $bGenre = $this->bookModelObj->insertNewGenre($bGenre);
                        }
                    }
                    //check if language exists. If not then insert new language.
                    if (!is_numeric($bookLang) && !empty($bookLang)) {
                        $languageName = locale_get_display_language($bookLang); //function to get language name by it's language code.
                        $checkLangExists = $this->bookModelObj->checkLangExists($languageName);
                        if ($checkLangExists) {
                            $bookLang = $this->bookModelObj->getLangId($languageName, $bookLang);
                        } else {
                            $bookLang = $this->bookModelObj->insertNewLang($languageName, $bookLang);
                        }
                    }

                    $isUploadingSameBook = $this->bookModelObj->sameBookBysameUser($user_id, $bName, $bGenre, $bookLang, $bAuthor, $edition, $publisher, $ISBN);
                    if ($isUploadingSameBook) {
                        $jsonMessage = [
                            "isSuccess" => false,
                            "message" => "A user can't upload same book twice."
                        ];
                        $response->getBody()->write(json_encode($jsonMessage));
                        return $response
                        ->withHeader("content-type", "application/json")
                        ->withStatus(200);
                    }
    
                    $addBookRst = $this->bookModelObj->addBook($bName, $bookImgLink, $bGenre, $bAuthor, $edition, $publisher, $description, $rating, $ISBN, $bookCondition, $bookLang, $review, $user_id);
            
                    if ($addBookRst) {
                        $jsonMessage = [
                            "isSuccess" => true,
                            "message" => "Book added Successfully"
                        ];
                        $response->getBody()->write(json_encode($jsonMessage));
                        return $response
                            ->withHeader("content-type", "application/json")
                            ->withStatus(200);
                    } else {
                        $jsonMessage = [
                            "isSuccess" => false,
                            "message" => "Book not uploaded successfully."
                        ];
                        $response->getBody()->write(json_encode($jsonMessage));
                        return $response
                            ->withHeader("content-type", "application/json")
                            ->withStatus(200);
                    }
    
                } else {
                    $jsonMessage = [
                        "isSuccess" => false,
                        "message" => "Only images are allowed  (jpg, png, jpeg)."
                    ];
                    $response->getBody()->write(json_encode($jsonMessage));
                    return $response
                    ->withHeader("content-type", "application/json")
                    ->withStatus(200);
                }
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Please upload image for book."
                ];
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
            }    
        //Updating book
        } else {
            $bookExists = $this->bookModelObj->checkBookExists($bookId);
            if (!$bookExists) {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Book doesn't exist."
                ];
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
            }
            $imgDetail = $this->bookModelObj->getBookDetailWithoutIssued($bookId);
            $bookImgLink = $imgDetail['image'];

            if (isset($_FILES['image']) && strlen($_FILES['image']['name']) != 0) {
                $allowedExt = ['png', 'jpg', 'jpeg'];
                $path = $_FILES['image']['name'];
                $imgExt = pathinfo($path, PATHINFO_EXTENSION);
                if (in_array($imgExt, $allowedExt)) {  
                    $bImage = $_FILES['image'];
                    $img_name = $bImage['name'];
                    $img_path = $bImage['tmp_name'];
                    $bookDest = __DIR__."/../img/books/".$img_name;
                    $bookImgLink = "app/img/books/".$img_name;
                    move_uploaded_file($img_path, $bookDest);
                } else {
                    $jsonMessage = [
                        "isSuccess" => false,
                        "message" => "Only images are allowed."
                    ];
                    $response->getBody()->write(json_encode($jsonMessage));
                    return $response
                    ->withHeader("content-type", "application/json")
                    ->withStatus(200);
                }
            }
            $editRst = $this->bookModelObj->updateBook($bName, $bookImgLink, $bGenre, $bAuthor, $edition, $publisher, $ISBN, $bookCondition, $bookLang, $description, $rating, $review, $bookId);
            if($editRst) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "Book Updated"
                ];
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Something error occured."
                ];
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
            } 
        }
    }

    

    public function deleteBook(Request $request, Response $response, $args)
    {                 
        $bookId = (int) $args['bookId'];
        $dltBookRst = $this->bookModelObj->deleteBook($bookId);
        if($dltBookRst) {
            $jsonMessage = array("isSuccess" => true,
            "message" => "Book deleted successfully");
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        } else {
            $jsonMessage = array("isSuccess" => false,
            "message" => "No book available with that id.");
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }

    }

    public function addReview(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['bookId']);
        $userId = (int)trim($params['userId'] ?? '');
        $bookReview = trim($params['review'] ?? '');
        $bookRating = (double)trim($params['rating'] ?? '');
        $bookExistsRst = $this->bookModelObj->checkBookExists($bookId);
        
        if($bookExistsRst) {
            $alreadyAddedReview = $this->bookModelObj->alreadyAddedReview($bookId, $userId);
            if ($alreadyAddedReview) {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "Review already recorded."
                );
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
            }
            $addReviewRst = $this->bookModelObj->addBookReview($userId, $bookReview, $bookId, $bookRating);
            if($addReviewRst) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Review recorded."
                );
                $response->getBody()->write(json_encode($jsonMessage));
                return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Book Doesn't exist."
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }
    }

    public function editReview(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['bookId']);
        $userId = (int)trim($params['userId'] ?? '');
        $bookReview = trim($params['review'] ?? '');
        $bookRating = (double)trim($params['rating'] ?? '');
        $bookExistsRst = $this->bookModelObj->checkBookExists($bookId);
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "Failed."
        );
        if($bookExistsRst) {
            $alreadyAddedReview = $this->bookModelObj->alreadyAddedReview($bookId, $userId);
            if ($alreadyAddedReview) {
                $updateReview = $this->bookModelObj->updateUserReview($bookId, $userId, $bookReview, $bookRating);
                if ($updateReview) {
                    $jsonMessage = array(
                        "isSuccess" => true,
                        "message" => "Review updated successfully."
                    );
                }
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Book Doesn't exist."
            );
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function personalBooks(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        $params = $request->getParsedBody();   
        $search = trim($params['search'] ?? '');
        $personalBooks = $this->bookModelObj->getPersonalBooks($userId, $search);
        if (count($personalBooks) > 0) {
            $jsonMessage = array("isSuccess" => true,
                                    "message" => "My books",
                                    "book" => $personalBooks);
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        } else {
            $jsonMessage = array("isSuccess" => false,
            "message" => "No personal books",
            "book" => null);
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }
    }


    public function getBookById(Request $request, Response $response, $args)
    {
        $bookId = (int)$args['bookId'];
        $params = $request->getParsedBody();
        $user_id = (int)trim($params['userId'] ?? '');
        $bookExists = $this->bookModelObj->checkBookExists($bookId);
        if ($bookExists) {
            $bookIssued = $this->bookModelObj->bookIssued($bookId);
            if ($bookIssued) {
                $bookDetail = $this->bookModelObj->getBookDetailWithIssed($bookId, $user_id);
            } else {
                $bookDetail = $this->bookModelObj->getBookDetailWithoutIssued($bookId, $user_id);
            }
            $bookDetail = $this->bookModelObj->isBookWishListedByAnyone($bookId, $bookDetail);
            $jsonMessage = array("isSuccess" => false,
            "message" => "Something went wrong.",
            "book" => []);
            if ($bookDetail) {
                $jsonMessage = array("isSuccess" => true,
                "message" => "Book details",
                "book" => $bookDetail);
            }
        } else {
            $jsonMessage = array("isSuccess" => false,
            "message" => "No books found.",
            "book" => []);
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function reviewList(Request $request, Response $response, $args)
    {
        $bookId = (int)$args['bookId'];
        $reviewList = $this->bookModelObj->reviewList($bookId);
        if (count($reviewList) > 0) {
            $jsonMessage = array(
            "isSuccess" => true,
            "message" => "Review list.",
            "reviewList" => $reviewList
        );
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);

        } else {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "No reviews found to this book.",
                "reviewList" => null
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }
    }

    public function deleteFeed(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $feedId = (int)trim($params['feed_id'] ?? '');
        $userId = (int)trim($params['user_id'] ?? '');
        $bookId = (int)trim($params['book_id'] ?? '');

        $deleteFeedRst = $this->bookModelObj->deleteFeed($feedId, $userId, $bookId);
        if ($deleteFeedRst) {
            $jsonMessage = array("isSuccess" => true,
            "message" => "Feed deleted");
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);

        } else {
            $jsonMessage = array("isSuccess" => true,
            "message" => "Sorry not able to delete");
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }
    }

    public function borrowHistory(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = (int)trim($params['user_id'] ?? '');
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No books borrowed till now.",
            "hostory" => null,
        ];
        $borrowHistory = $this->bookModelObj->borrowHistory($userId);
        if (!empty($borrowHistory)) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Borrow history",
                "history" => $borrowHistory
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function lendingHistory(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = (int)trim($params['user_id'] ?? '');
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "Something went wrong.",
            "hostory" => null,
        ];
        $lendingHistory = $this->bookModelObj->lendingHistory($userId);
        if (!empty($lendingHistory)) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Lending history",
                "history" => $lendingHistory
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function addRemoveFavourite(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $isAdd = (int)trim($params['isAdd'] ?? '');
        $bookId = (int)trim($params['book_id'] ?? '');
        $userId = (int)$args['user_id'];
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "Something Went Wrong"
        ];
        if ($isAdd == 1) {
            $addToWishList = $this->bookModelObj->addToWishList($bookId, $userId);
            if ($addToWishList) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "Book added to favourite."
                ];
            }
        } elseif ($isAdd == 0) {
            $removeFromWishList = $this->bookModelObj->removeFromWishList($bookId, $userId);
            if ($removeFromWishList) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "Removed from favourite."
                ];
            }
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function showWishlist(Request $request, Response $response, $args)
    {
        $userId = (int)$args['user_id'];
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No wishlist.",
            "wishlist" => []
        ];
        $wishListBooks = $this->bookModelObj->showWishlistBooks($userId);
        if (count($wishListBooks) > 0) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "showing wishlist.",
                "wishlist" => $wishListBooks
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function getBooksByGenre(Request $request, Response $response, $args)
    {
        $genreName = $args['genre_name'];
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No books with that genre.",
            "book" => null
        ];
        $genreBooks = $this->bookModelObj->getBooksByGenre($genreName);
        if (count($genreBooks) > 0) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Book list.",
                "book" => $genreBooks
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function searchBook(Request $request, Response $response, $args)
    {
        $findBook = $args['bookQuery'];
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No books found.",
            "foundBooks" => []
        ];
        $foundBooks = $this->bookModelObj->searchBook($findBook);
        if (count($foundBooks) > 0) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Found books",
                "foundBooks" => $foundBooks
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function getBookEditions(Request $request, Response $response, $args)
    {
        $bookName = trim($args['bookName']);
        $getEditions = $this->bookModelObj->getBookEditions($bookName);
        $allBookEditions = (count($getEditions) > 0) ? $getEditions : [];
        $jsonMessage = [
            "isSuccess" => true,
            "message" => "Edition list",
            "editionList" => $allBookEditions
        ];
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function getSharedUsers(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = trim($params['userId'] ?? '');
        $bookName = trim($params['title'] ?? '');
        $author = trim($params['author'] ?? '');
        $publisher = trim($params['publisher'] ?? '');
        $isbn = trim($params['ISBN'] ?? '');
        $offset = (int)trim($params['offset'] ?? '');
        $limit = (int)trim($params['limit'] ?? '');

        $ownerIds = array_unique($this->bookModelObj->getOwnerId($bookName, $author, $publisher, $isbn));
        foreach ($ownerIds as $ownerId) {
            $userAndBookDetails[] = $this->bookModelObj->getUserAndBookDetails($userId, $ownerId, $bookName, $author, $publisher, $isbn);
        }
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "List of shared users",
            "sharedUserList" => []
        ];
        if (!empty($userAndBookDetails)) {
            $numberOfUsers = array_slice($userAndBookDetails, 0, ($offset+$limit));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of shared users",
                "sharedUserList" => $numberOfUsers
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function searchBooks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $isAuthor = (int)trim($params['isAuthor'] ?? '');
        $search = trim($params['search'] ?? '');
        $languageId = (int)trim($params['languageId'] ?? '');
        $genreId = (int)trim($params['genreId'] ?? '');
        $offset = (int)trim($params['offset'] ?? '');
        $limit = (int)trim($params['limit'] ?? '');
        if (!empty($search)) {
            $searchResult = $this->bookModelObj->searchBooks($isAuthor, $search, $languageId, $genreId);
            $searchResult = $this->bookModelObj->makeBookListUniqueByBookName($searchResult);        
            if (!empty($searchResult)) {
                for ($i = 0; $i<count($searchResult); $i++) {
                    $isBookWishListed = $this->bookModelObj->isBookWishListed($searchResult[$i]['bookId']);
                    $searchResult[$i]['isWishList'] = $isBookWishListed;//if book is wishlisted by anyone.
                    $noOfReviews = $this->bookModelObj->getNoOfReviews($searchResult[$i]['bookId']);
                    $searchResult[$i]['noOfReviews'] = $noOfReviews;//the number of reviews given for this book.
                    $noOfSharedUsers = $this->bookModelObj->getSharedNoOfUsers(
                        $searchResult[$i]['title'],
                        $searchResult[$i]['author'],
                        $searchResult[$i]['languageId'],
                        $searchResult[$i]['genreId'],
                        $searchResult[$i]['isbn'],
                    );
                    $searchResult[$i]['noOfSharingUsers'] = $noOfSharedUsers;// number of users having same book.
                }
            }
        }
        if (isset($searchResult) && count($searchResult) > 0) {
            $showSearchData = array_slice($searchResult, 0, ($offset+$limit));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of books",
                "searchList" => $showSearchData
            ];
        } else {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "List of books",
                "searchList" => []
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function getUserReview(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = (int)trim($params['userId'] ?? '');
        $bookId = (int)trim($params['bookId'] ?? '');
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No review found.",
            "review" => "",
            "rating" => null
        ];
        $getReviewRating = $this->bookModelObj->getUserReview($userId, $bookId);
        if (!empty($getReviewRating)) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Review found.",
                "review" => $getReviewRating[0],
                "rating" => $getReviewRating[1]
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }
}
