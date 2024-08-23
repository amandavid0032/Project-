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

        $bookDetails = $this->extractBookDetails($params);

        if ($this->isUpdatingBook($bookId)) {
            return $this->updateBook($response, $bookId, $bookDetails);
        }

        if ($this->isISBNExists($bookDetails['isbn'], $bookId)) {
            return $this->addCopyByISBN($response, $bookDetails['isbn'], $bookDetails);
        }

        if ($this->isBookCombinationExists($bookDetails, $bookId)) {
            return $this->addCopyByCombination($response, $bookDetails);
        }

        return $this->addNewBook($response, $bookDetails);
    }

    private function extractBookDetails($params)
    {
        return [
            'name' => trim($params['name'] ?? ''),
            'genre' => trim($params['genre'] ?? ''),
            'author' => trim($params['author'] ?? ''),
            'edition' => (int)trim($params['edition'] ?? ''),
            'publisher' => trim($params['publisher'] ?? ''),
            'rating' => trim($params['rating'] ?? ''),
            'description' => trim($params['description'] ?? ''),
            'isbn' => trim($params['isbn'] ?? ''),
            'user_id' => trim($params['userid'] ?? ''),
            'condition' => (int)trim($params['condition'] ?? ''),
            'language' => trim($params['language'] ?? ''),
            'review' => trim($params['review'] ?? ''),
            'noOfBooks' => trim($params['noOfBooks'] ?? ''),
            'latitude' => trim($params['latitude'] ?? ''),
            'longitude' => trim($params['longitude'] ?? ''),
            'image' => $this->handleImageUpload()
        ];
    }

    private function handleImageUpload()
    {
        if (isset($_FILES['image']) && strlen($_FILES['image']['name']) != 0) {
            $allowedExt = ['png', 'jpg', 'jpeg'];
            $path = $_FILES['image']['name'];
            $imgExt = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($imgExt, $allowedExt)) {
                $bImage = $_FILES['image'];
                $img_name = $bImage['name'];
                $img_path = $bImage['tmp_name'];
                $bookDest = __DIR__ . "/../img/books/" . $img_name;
                $bookImgLink = "app/img/books/" . $img_name;
                move_uploaded_file($img_path, $bookDest);
                return $bookImgLink;
            }
        }
        return "0";
    }

    private function isUpdatingBook($bookId)
    {
        return $bookId != 0;
    }

    private function isISBNExists($isbn, $bookId)
    {
        return !empty($isbn) && $this->bookModelObj->checkISBNExists($isbn, $bookId);
    }

    private function isBookCombinationExists($bookDetails, $bookId)
    {
        return $this->bookModelObj->checkBookCombinationExists($bookDetails['name'], $bookDetails['author'], $bookDetails['publisher'], $bookDetails['edition'], $bookId);
    }

    private function addCopyByISBN($response, $isbn, $bookDetails)
    {
        $originalBookId = $this->bookModelObj->getBookIdByISBN($isbn);
        $this->bookModelObj->addBookCopy($originalBookId, $bookDetails['user_id'], $bookDetails['edition'], $bookDetails['rating'], $bookDetails['condition'], $bookDetails['review'], $bookDetails['noOfBooks'], $bookDetails['latitude'], $bookDetails['longitude']);
        return $this->jsonResponse($response, true, "ISBN already exists, added as a copy.");
    }

    private function addCopyByCombination($response, $bookDetails)
    {
        $originalBookId = $this->bookModelObj->getOriginalBookId($bookDetails['name'], $bookDetails['author'], $bookDetails['publisher'], $bookDetails['edition']);
        $this->bookModelObj->addBookCopy($originalBookId, $bookDetails['user_id'], $bookDetails['edition'], $bookDetails['rating'], $bookDetails['condition'], $bookDetails['review'], $bookDetails['noOfBooks'], $bookDetails['latitude'], $bookDetails['longitude']);
        return $this->jsonResponse($response, true, "Book already exists, added as a copy.");
    }

    private function addNewBook($response, $bookDetails)
    {
        if ($bookDetails['image'] === "0") {
            return $this->jsonResponse($response, false, "Please upload an image for the book.");
        }

        // Handle genre and language
        $bookDetails['genre'] = $this->handleGenre($bookDetails['genre']);
        $bookDetails['language'] = $this->handleLanguage($bookDetails['language']);

        // Check if the same user is uploading the same book
        if ($this->bookModelObj->sameBookBysameUser($bookDetails['user_id'], $bookDetails['name'], $bookDetails['genre'], $bookDetails['language'], $bookDetails['author'], $bookDetails['edition'], $bookDetails['publisher'], $bookDetails['isbn'])) {
            return $this->jsonResponse($response, false, "A user can't upload the same book twice.");
        }

        $addBookRst = $this->bookModelObj->addBook($bookDetails['name'], $bookDetails['image'], $bookDetails['genre'], $bookDetails['author'], $bookDetails['edition'], $bookDetails['publisher'], $bookDetails['description'], $bookDetails['rating'], $bookDetails['isbn'], $bookDetails['condition'], $bookDetails['language'], $bookDetails['review'], $bookDetails['user_id']);

        if ($addBookRst) {
            $newBookId = $this->bookModelObj->getLastInsertId();
            $this->bookModelObj->addBookCopy($newBookId, $bookDetails['user_id'], $bookDetails['edition'], $bookDetails['rating'], $bookDetails['condition'], $bookDetails['review'], $bookDetails['noOfBooks'], $bookDetails['latitude'], $bookDetails['longitude']);
            return $this->jsonResponse($response, true, "Book added successfully.");
        }

        return $this->jsonResponse($response, false, "Book not uploaded successfully.");
    }

    private function handleGenre($genre)
    {
        if (!is_numeric($genre)) {
            if ($this->bookModelObj->checkGenreExist($genre)) {
                return $this->bookModelObj->getGenerId($genre);
            }
            return $this->bookModelObj->insertNewGenre($genre);
        }
        return $genre;
    }

    private function handleLanguage($language)
    {
        if (!is_numeric($language) && !empty($language)) {
            $languageName = locale_get_display_language($language);
            if ($this->bookModelObj->checkLangExists($languageName)) {
                return $this->bookModelObj->getLangId($languageName, $language);
            }
            return $this->bookModelObj->insertNewLang($languageName, $language);
        }
        return $language;
    }

    private function jsonResponse($response, $isSuccess, $message)
    {
        $jsonMessage = [
            "isSuccess" => $isSuccess,
            "message" => $message
        ];
        $response->getBody()->write(json_encode($jsonMessage));
        return $response->withHeader("content-type", "application/json")->withStatus(200);
    }

    private function updateBook($response, $bookId, $bookDetails)
    {
        $bookExists = $this->bookModelObj->checkBookExists($bookId);
        if (!$bookExists) {
            return $this->jsonResponse($response, false, "Book doesn't exist.");
        }

        if ($this->isISBNExists($bookDetails['isbn'], $bookId)) {
            return $this->jsonResponse($response, false, "Cannot update book added by ISBN.");
        }

        $editRst = $this->bookModelObj->updateBook(
            (int)$bookDetails['edition'],
            (int)$bookDetails['condition'],
            $bookDetails['description'],
            $bookDetails['rating'],
            $bookDetails['review'],
            $bookId,
            $bookDetails['user_id']
        );

        if ($editRst) {
            return $this->jsonResponse($response, true, "Book updated.");
        }

        return $this->jsonResponse($response, false, "Failed to Update Book.");
    }






    public function deleteBook(Request $request, Response $response, $args)
    {
        $bookId = (int) $args['bookId'];
        $params = $request->getParsedBody();
        $userId = (int) trim($params['userId'] ?? '');

        $dltBookRst = $this->bookModelObj->deleteBook($bookId, $userId);

        if ($dltBookRst['success']) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => $dltBookRst['message']
            );
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => $dltBookRst['message']
            );
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }

    public function addReview(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['bookId']);
        $userId = (int)trim($params['userId'] ?? '');
        $bookReview = trim($params['review'] ?? '');
        $bookRating = (float)trim($params['rating'] ?? '');
        $bookExistsRst = $this->bookModelObj->checkBookExists($bookId);

        if ($bookExistsRst) {
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
            if ($addReviewRst) {
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
        $bookRating = (float)trim($params['rating'] ?? '');
        $bookExistsRst = $this->bookModelObj->checkBookExists($bookId);
        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "Failed."
        );
        if ($bookExistsRst) {
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
        $userId = (int) $args['userId'];
        $params = $request->getParsedBody();
        $search = trim($params['search'] ?? '');
        $personalBooks = $this->bookModelObj->getPersonalBooks($userId, $search);

        if (count($personalBooks) > 0) {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "My books",
                "book" => $personalBooks
            );
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "No personal books",
                "book" => null
            );
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
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
            $bookDetail = $this->bookModelObj->isBookWishListedByUser($bookId, $user_id, $bookDetail);

            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "Something went wrong.",
                "book" => []
            );

            if ($bookDetail) {
                $jsonMessage = array(
                    "isSuccess" => true,
                    "message" => "Book details",
                    "book" => $bookDetail
                );
            }
        } else {
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "No books found.",
                "book" => []
            );
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
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Feed deleted"
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } else {
            $jsonMessage = array(
                "isSuccess" => true,
                "message" => "Sorry not able to delete"
            );
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
            "history" => null,
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
        $offset = (int) trim($params['offset'] ?? '');
        $limit = (int) trim($params['limit'] ?? '');

        $ownerIds = array_unique($this->bookModelObj->getOwnerId($bookName, $author, $publisher, $isbn));
        $userAndBookDetails = [];

        foreach ($ownerIds as $ownerId) {
            $userDetails = $this->bookModelObj->getUserAndBookDetails($userId, $ownerId, $bookName, $author, $publisher, $isbn);
            if (!$this->isUserBlockedForAnyBook($userId, $ownerId, $userDetails['ownerBookInfo'])) {
                $userAndBookDetails[] = $userDetails;
            }
        }

        $jsonMessage = [
            "isSuccess" => true,
            "message" => "List of shared users",
            "sharedUserList" => array_slice($userAndBookDetails, $offset, $limit)
        ];

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }


    private function isUserBlockedForAnyBook(int $userId, int $ownerId, array $books): bool
    {
        foreach ($books as $book) {
            if (!isset($book['bookId'])) {
                continue; // Skip if bookId is not set
            }
            $bookId = (int) $book['bookId'];
            if ($this->bookModelObj->isUserBlockedForBook($userId, $ownerId, $bookId)) {
                return true;
            }
        }
        return false;
    }

    public function searchBooks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $isAuthor = (int)($params['isAuthor'] ?? 0);
        $search = trim($params['search'] ?? '');
        $languageId = (int)($params['languageId'] ?? 0);
        $genreId = (int)($params['genreId'] ?? 0);
        $offset = (int)($params['offset'] ?? 0);
        $limit = (int)($params['limit'] ?? 10); 
        $searchResult = $this->bookModelObj->searchBooks($isAuthor, $search, $languageId, $genreId);
        $searchResult = $this->bookModelObj->makeBookListUniqueByBookName($searchResult);
        if (!empty($searchResult)) {
            for ($i = 0; $i < count($searchResult); $i++) {
                $isBookWishListed = $this->bookModelObj->isBookWishListed($searchResult[$i]['bookId']);
                $searchResult[$i]['isWishList'] = $isBookWishListed;
                $noOfReviews = $this->bookModelObj->getNoOfReviews($searchResult[$i]['bookId']);
                $searchResult[$i]['noOfReviews'] = $noOfReviews;
                $noOfSharedUsers = $this->bookModelObj->getSharedNoOfUsers(
                    $searchResult[$i]['title'],
                    $searchResult[$i]['author'],
                    $searchResult[$i]['languageId'],
                    $searchResult[$i]['genreId'],
                    $searchResult[$i]['isbn']
                );
                $searchResult[$i]['noOfSharingUsers'] = $noOfSharedUsers;
            }
        }
        if (!empty($searchResult)) {
            $showSearchData = array_slice($searchResult, $offset, $limit);
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of books",
                "searchList" => $showSearchData
            ];
        } else {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "There is no Book Found",
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


    public function blockUserForBook(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = trim($params['userId'] ?? '');
        $ownerId = trim($params['ownerId'] ?? '');
        $bookId = trim($params['bookId'] ?? '');
        $blockReason = trim($params['blockReason'] ?? '');
        $valid = true;
        $errorMessage = "";

        if (!$this->bookModelObj->checkEntityExists('user', $userId)) {
            $errorMessage .= "Invalid userId provided ";
            $valid = false;
        }

        if (!$this->bookModelObj->checkEntityExists('user', $ownerId)) {
            $errorMessage .= "Invalid ownerId provided  ";
            $valid = false;
        }

        if (!$this->bookModelObj->checkEntityExists('book', $bookId)) {
            $errorMessage .= "Invalid bookId provided  ";
            $valid = false;
        }

        if (empty($blockReason)) {
            $errorMessage .= "Block reason is required ";
            $valid = false;
        }

        if (!$valid) {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => rtrim($errorMessage)
            ];
            $status = 400;
        } else {
            if ($this->bookModelObj->isUserBlockedForBook($userId, $ownerId, $bookId)) {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "User is already blocked for this book"
                ];
                $status = 400;
            } else {
                $result = $this->bookModelObj->blockUserForBook($userId, $ownerId, $bookId, $blockReason);

                if ($result) {
                    $jsonMessage = [
                        "isSuccess" => true,
                        "message" => "User blocked for book successfully"
                    ];
                    $status = 200;
                } else {
                    $jsonMessage = [
                        "isSuccess" => false,
                        "message" => "Failed to block user for book"
                    ];
                    $status = 400;
                }
            }
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus($status);
    }



    public function unblockUserForBook(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = trim($params['userId'] ?? '');
        $ownerId = trim($params['ownerId'] ?? '');
        $bookId = trim($params['bookId'] ?? '');

        $valid = true;
        $errorMessage = "";

        if (!$this->bookModelObj->checkEntityExists('user', $userId)) {
            $errorMessage .= "Invalid userId provided ";
            $valid = false;
        }

        if (!$this->bookModelObj->checkEntityExists('user', $ownerId)) {
            $errorMessage .= "Invalid ownerId provided ";
            $valid = false;
        }

        if (!$this->bookModelObj->checkEntityExists('book', $bookId)) {
            $errorMessage .= "Invalid bookId provided";
            $valid = false;
        }

        if (!$valid) {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => rtrim($errorMessage)
            ];
            $status = 400; 
        } else {
            $result = $this->bookModelObj->unblockUserForBook($userId, $ownerId, $bookId);

            if ($result) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "User unblocked for book successfully"
                ];
                $status = 200;
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Failed to unblock user for book"
                ];
                $status = 400; 
            }
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus($status);
    }


    public function getBlockedBooks(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = trim($params['userId'] ?? '');
        if (!$this->bookModelObj->checkEntityExists('user', $userId)) {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "Invalid userId provided "
            ];
            $status = 404;
        } else {
            $blockedBooks = $this->bookModelObj->getBlockedBooks($userId);
            if (!empty($blockedBooks)) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "List of blocked books",
                    "blockedBooks" => $blockedBooks
                ];
                $status = 200;
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "No blocked books found."
                ];
                $status = 400; 
            }
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus($status);
    }

}
