<?php
namespace App\Model;

class BookModel
{
    public const SHOW_RECENT_ADDED_BOOKS = 10;
    public const ISSUED_STATUS = 1;
    public const RETURNED_STATUS = 3;
    public const RETURNING_STATUS = 2;
    public const REQUESTED_STATUS = 0;
    public const BOOKS_TO_FIND = 7;

    protected $conn;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserLangIds(int $userId)
    {
        $langArr = []; //to store user selected languages
        $langStmt = $this->conn->prepare("select * from user_lang where user_id = ?");
        $langStmt->bind_param("i", $userId);
        $langStmt->execute();
        $langStmtRst = $langStmt->get_result();
        while($row = $langStmtRst->fetch_assoc()) {
            $r = $row['lang_id'];
            array_push($langArr, $r);
        }
        return $langArr;
    }

    public function getUserGenreIds(int $userId)
    {
        $genreArr = []; //to store user selected languages
        $genreStmt = $this->conn->prepare("select * from user_genre where user_id = ?");
        $genreStmt->bind_param("i", $userId);
        $genreStmt->execute();
        $genreStmtRst = $genreStmt->get_result();
        while($row = $genreStmtRst->fetch_assoc()) {
            $r = $row['genre_id'];
            array_push($genreArr, $r);
        }
        return $genreArr;
    }

    public function getBooksWithGenreAndLang($userLangIds, $userGenreIds)
    {
        if (count($userLangIds) == 0 && count($userGenreIds) == 0) {
            return [];
        }
        $books = BookModel::SHOW_RECENT_ADDED_BOOKS;
        $userLangIds = implode(",", $userLangIds);
        $userGenreIds = implode(",", $userGenreIds);
        $recentlyAddedBooksWithGenreAndLang = [];
        $recentlyAddedStmt = $this->conn->prepare("
        SELECT max(b.id) as id, b.book_name, max(b.image) as image, max(b.genre) as genre, b.author, max(b.edition) as edition, b.publisher, max(b.description) as description, max(b.status) as status,
        max(b.rating) as rating, max(b.owner_id) as owner_id, max(b.isbn) as isbn, max(b.book_status) as book_status, max(b.book_condition) as book_condition, b.book_lang, max(b.review) as review,
        max(b.noOfBooks) as noOfBooks, max(b.lattitude) as lattitude, max(b.longitude) as longitude, max(b.upload_date) as upload_date, max(g.genre) as genre, max(l.name) as bookLanguage
        FROM books as b
        INNER JOIN genre as g ON g.id = b.genre
        INNER JOIN language as l ON l.id = b.book_lang
        where (b.book_lang IN ($userLangIds) and b.genre IN ($userGenreIds))
        GROUP BY b.book_name, b.author, b.publisher, b.book_lang
        ORDER BY upload_date desc LIMIT ?");
        $recentlyAddedStmt->bind_param("i",$books);
        $recentlyAddedStmt->execute();
        $recentlyAddedStmtRst = $recentlyAddedStmt->get_result();
        if ($recentlyAddedStmtRst->num_rows > 0) {
            $recentlyAddedBooksWithGenreAndLang = $recentlyAddedStmtRst->fetch_all(MYSQLI_ASSOC);
            $recentlyAddedBooksWithGenreAndLangArray = $this->getSimilarGenres($recentlyAddedBooksWithGenreAndLang);
        }
        return $recentlyAddedBooksWithGenreAndLangArray;
    }

    public function getSimilarGenres(array $bookList) {
        $updatedBookArray = [];
        foreach ($bookList as $book) {
            $getSimilarBookStmt = $this->conn->prepare("SELECT g.genre
            from books as b
            INNER JOIN genre as g on b.genre = g.id
            where b.book_name = ? and b.author = ? and b.publisher = ? and b.book_lang = ?");
            $getSimilarBookStmt->bind_param("sssi", $book['book_name'], $book['author'], $book['publisher'], $book['book_lang']);
            $getSimilarBookStmt->execute();
            $similarResult = $getSimilarBookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $generNameArray = [];
            foreach ($similarResult as $sBook) {
                if (!in_array($sBook['genre'], $generNameArray)) {
                    $generNameArray[] = $sBook['genre'];
                }
            }
            $book['gNames'] = implode(", ",$generNameArray);
            $updatedBookArray[] = $book;
        }
        return $updatedBookArray;
    }

    public function getLatestUploadBooks() : array
    {
        $latestBooks = [];
        $books = BookModel::SHOW_RECENT_ADDED_BOOKS;
        $latestBookStmt = $this->conn->prepare("
        SELECT max(b.id) as id, b.book_name, max(b.image) as image, max(b.genre) as genre, b.author, max(b.edition) as edition, b.publisher, max(b.description) as description, max(b.status) as status,
        max(b.rating) as rating, max(b.owner_id) as owner_id, max(b.isbn) as isbn, max(b.book_status) as book_status, max(b.book_condition) as book_condition, b.book_lang, max(b.review) as review,
        max(b.noOfBooks) as noOfBooks, max(b.lattitude) as lattitude, max(b.longitude) as longitude, max(b.upload_date) as upload_date, max(g.genre) as genre, max(l.name) as bookLanguage
        from books as b
        inner join genre as g on g.id = b.genre
        INNER JOIN language as l ON l.id = b.book_lang
        GROUP BY b.book_name, b.author, b.publisher, b.book_lang
        ORDER BY upload_date desc LIMIT ?");
        $latestBookStmt->bind_param("i", $books);
        $latestBookStmt->execute();
        $latestBooks = $latestBookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $latestBooksArray = $this->getSimilarGenres($latestBooks);
        return $latestBooksArray;
    }

    public function getBooksWithLang(array $userLangIdsArray): array
    {
        if (count($userLangIdsArray) == 0) {
            return [];
        }

        $recentlyAdded = [];
        $recentlyAddedArray = [];  // Initialize $recentlyAddedArray to an empty array
        $books = BookModel::SHOW_RECENT_ADDED_BOOKS;
        $userLangIds = implode(",", $userLangIdsArray);

        $recentlyAddedStmt = $this->conn->prepare("
        SELECT max(b.id) as id, b.book_name, max(b.image) as image, max(b.genre) as genre, b.author, max(b.edition) as edition, b.publisher, max(b.description) as description, max(b.status) as status,
        max(b.rating) as rating, max(b.owner_id) as owner_id, max(b.isbn) as isbn, max(b.book_status) as book_status, max(b.book_condition) as book_condition, b.book_lang, max(b.review) as review,
        max(b.noOfBooks) as noOfBooks, max(b.lattitude) as lattitude, max(b.longitude) as longitude, max(b.upload_date) as upload_date, max(g.genre) as genre, max(l.name) as bookLanguage
        FROM books as b
        INNER JOIN genre as g ON g.id = b.genre
        INNER JOIN language as l ON l.id = b.book_lang
        WHERE book_lang IN ($userLangIds)
        GROUP BY b.book_name, b.author, b.publisher, b.book_lang
        ORDER BY upload_date DESC LIMIT ?");
        $recentlyAddedStmt->bind_param("i", $books);
        $recentlyAddedStmt->execute();
        $recentlyAddedStmtRst = $recentlyAddedStmt->get_result();
        if ($recentlyAddedStmtRst->num_rows > 0) {
            $recentlyAdded = $recentlyAddedStmtRst->fetch_all(MYSQLI_ASSOC);
            $recentlyAddedArray = $this->getSimilarGenres($recentlyAdded);
        }

        return $recentlyAddedArray;
    }


    public function getPopularBooks(array $userLangIdsArray): array
    {
        $popularBooksArray = [];
        $books = BookModel::SHOW_RECENT_ADDED_BOOKS;
        $popularBooks = [];

        // Create the base query
        $query = "SELECT max(b.id) as id, b.book_name, max(b.image) as image, max(b.genre) as genre, b.author, max(b.edition) as edition, b.publisher, max(b.description) as description, max(b.status) as status,
    max(b.rating) as rating, max(b.owner_id) as owner_id, max(b.isbn) as isbn, max(b.book_status) as book_status, max(b.book_condition) as book_condition, b.book_lang, max(b.review) as review,
    max(b.noOfBooks) as noOfBooks, max(b.lattitude) as lattitude, max(b.longitude) as longitude, max(b.upload_date) as upload_date, count(r.book_id) as bookc, max(g.genre) as genre, max(l.name) as bookLanguage
    FROM books as b
    INNER JOIN genre as g ON g.id = b.genre
    INNER JOIN language as l ON l.id = b.book_lang
    INNER JOIN (SELECT book_id FROM request) as r on r.book_id = b.id";

        // If userLangIdsArray is not empty, add the WHERE clause
        if (!empty($userLangIdsArray)) {
            $userLangIds = implode(",", $userLangIdsArray);
            $query .= " WHERE b.book_lang IN ($userLangIds)";
        }

        // Complete the query
        $query .= " GROUP BY r.book_id, b.book_name, b.author, b.publisher, b.book_lang
    ORDER BY count(*) DESC, upload_date DESC, r.book_id
    LIMIT ?";

        // Prepare and execute the query
        $popularBooksStmt = $this->conn->prepare($query);
        $popularBooksStmt->bind_param("i", $books);
        $popularBooksStmt->execute();
        $popularBooksStmtRst = $popularBooksStmt->get_result();

        if ($popularBooksStmtRst->num_rows > 0) {
            $popularBooks = $popularBooksStmtRst->fetch_all(MYSQLI_ASSOC);
            $popularBooksArray = $this->getSimilarGenres($popularBooks);
        }

        return $popularBooksArray;
    }


    public function getUserGenreBooks(int $userId) : array
    {
        $genreArr = []; //to store user selected Genres
        $GenreBookList = [];//to store books selected by user according to genre
        $booksNumber = BookModel::SHOW_RECENT_ADDED_BOOKS;
        $genreStmt = $this->conn->prepare("select * from user_genre where user_id = ?");
        $genreStmt->bind_param("i", $userId);
        $genreStmt->execute();
        $genreStmtRst = $genreStmt->get_result();
        while($row = $genreStmtRst->fetch_assoc()) {
            $r = $row['genre_id'];
            array_push($genreArr, $r);
        }
        foreach ($genreArr as $genreId) {
            $books = [];
            $bookByGenreIdStmt = $this->conn->prepare(
            "select max(b.id) as id, b.book_name, max(b.image) as image, max(b.genre) as genre, b.author, max(b.edition) as edition, b.publisher, max(b.description) as description, max(b.status) as status,
            max(b.rating) as rating, max(b.owner_id) as owner_id, max(b.isbn) as isbn, max(b.book_status) as book_status, max(b.book_condition) as book_condition, b.book_lang, max(b.review) as review,
            max(b.noOfBooks) as noOfBooks, max(b.lattitude) as lattitude, max(b.longitude) as longitude, max(b.upload_date) as upload_date, max(g.genre) as genre, max(l.name) as bookLanguage
            from books as b
            inner join genre as g on b.genre = g.id
            INNER JOIN language as l ON l.id = b.book_lang
            where b.genre = ?
            GROUP BY b.book_name, b.author, b.publisher, b.book_lang
            ORDER BY upload_date DESC LIMIT ?");
            $bookByGenreIdStmt->bind_param("si", $genreId, $booksNumber);
            $bookByGenreIdStmt->execute();
            $books = $bookByGenreIdStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $GenreBookListArray = $this->getSimilarGenres($books);
            foreach($GenreBookListArray as $book) {
                $GenreBookList[$book['genre']][] = $book;
            }
        }
        return $GenreBookList;
    }

    public function listAllBooks(array $recentlyAdded, array $popularBooks, array $userGenreBooks) : array
    {
        $bookLists = [
            ['name'=>'Recently Added', 'books'=>$recentlyAdded],
            ['name'=>'Most Popular', 'books'=>$popularBooks]
        ];
        foreach ($userGenreBooks as $key => $val) {
            $generBooks = ['name' => $key, 'books' => $val];
            array_push($bookLists, $generBooks);
            $generBooks = [];
        }
        return $bookLists;
    }

    public function addBook(
        string $bName,
        string $bookDest,
        string $bGenre,
        string $bAuthor,
        int $edition,
        String $publisher,
        string $description,
        string $rating,
        string $ISBN,
        int $bookCondition,
        string $bookLang,
        string $review,
        string $ownerId
    ) : bool
    {
        $addBookQry = $this->conn->prepare("INSERT INTO books (book_name, image, genre, author, edition, publisher, description, rating, isbn, book_condition, book_lang, review, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $addBookQry->bind_param("ssssissssisss", $bName, $bookDest, $bGenre, $bAuthor, $edition, $publisher, $description, $rating, $ISBN, $bookCondition, $bookLang, $review, $ownerId);
        $addBookQry->execute();
        if ($addBookQry->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateBook(
        string $bName,
        string $bookDest,
        int $bGenre,
        string $bAuthor,
        int $edition,
        string $publisher,
        string $ISBN,
        int $bookCondition,
        int $bookLang,
        string $description,
        string $rating,
        string $review,
        int $bookId
        ) : bool
    {
        $updateQry = $this->conn->prepare("update books set book_name = ?, image = ?, genre = ?, author = ?, edition = ?, publisher = ?, isbn = ?, book_condition = ?, book_lang = ?, description = ?, review = ?, rating = ? where id = ?");
        $updateQry->bind_param("ssisissiisssi", $bName, $bookDest, $bGenre, $bAuthor, $edition, $publisher, $ISBN, $bookCondition, $bookLang, $description, $review, $rating, $bookId);
        $updateQry->execute();
        if ($updateQry->affected_rows > 0) {         
            return true;
        } else {
            return false;
        }
    }

    public function deleteBook(int $bookId) : bool
    {
        $checkQry = $this->conn->prepare("select * from books where id = ?");
        $checkQry->bind_param("i", $bookId);
        $checkQry->execute();
        if ($checkQry->get_result()->num_rows > 0) {
            $dltQry = $this->conn->prepare("delete from books where id = ?");
            $dltQry->bind_param("i", $bookId);
            $dltQry->execute();
            return true;
        } else {
            return false;
        }
    }

    public function checkBookExists(string $bookId) : bool
    {
        $existStmt = $this->conn->prepare("select * from books where id = ?");
        $existStmt->bind_param("s", $bookId);
        $existStmt->execute();
        if($existStmt->get_result()->num_rows > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function addBookReview(int $userId, string $review, int $bookId, float $bookRating) : bool
    {
        $insrtBookReview = $this->conn->prepare("insert into feedback(feedback, user_id, book_id, book_rating) VALUES (?, ?, ?, ?)");
        $insrtBookReview->bind_param("siid", $review, $userId, $bookId, $bookRating);
        $insrtBookReview->execute();
        if ($insrtBookReview->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserReview(int $bookId, int $userId, string $bookReview, float $bookRating) : bool
    {
        $updateBookReview = $this->conn->prepare("
            UPDATE feedback set feedback = ?, book_rating = ?
            WHERE user_id = ? and book_id = ?
        ");
        $updateBookReview->bind_param("sdii", $bookReview, $bookRating, $userId, $bookId);
        $updateBookReview->execute();
        if ($updateBookReview->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPersonalBooks(int $userId, string $search) : array
    {
        if ($search == '') {
            $personalBooks = $this->conn->prepare("select b.*, g.genre
            from books as b
            inner join genre as g on b.genre = g.id
            where b.owner_id = ? order by upload_date desc");
            $personalBooks->bind_param("s", $userId);
        } else {
            $search_qry = "%$search%";
            $personalBooks = $this->conn->prepare("select b.*, g.genre
            from books as b
            inner join genre as g on b.genre = g.id
            where (b.book_name LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?) and (b.owner_id = ?)
            order by b.upload_date desc");
            $personalBooks->bind_param("sssi", $search_qry, $search_qry, $search_qry, $userId);
        }
        $personalBooks->execute();
        $myBooks = $personalBooks->get_result()->fetch_all(MYSQLI_ASSOC);
        return $myBooks;
    }

    public function searchBook(string $findBook)
    {
        $book = "%$findBook%";
        $foundBooks = [];
        $numberOfBooks = BookModel::BOOKS_TO_FIND;
        $searchQry = $this->conn->prepare("
        select b.id, b.image as thumbnail, b.book_name as title, b.author, l.name as language, g.genre, b.publisher
            from books as b
            inner join genre as g on b.genre = g.id
            inner join language as l on b.book_lang = l.id
            where b.book_name LIKE ? OR b.author LIKE ? OR b.isbn LIKE ? LIMIT ?");
        $searchQry->bind_param("sssi", $book, $book, $book, $numberOfBooks);
        $searchQry->execute();
        $foundBooks = $searchQry->get_result()->fetch_all(MYSQLI_ASSOC);
        return $foundBooks;
    }


    public function bookIssued(int $bookId) : bool
    {
        $issuedStatus = BookModel::ISSUED_STATUS;
        $findCount = $this->conn->prepare("select * from request where book_id = ? and status = ?");
        $findCount->bind_param("ii", $bookId, $issuedStatus);
        $findCount->execute();
        $rstSet = $findCount->get_result();
        if ($rstSet->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getBookDetailWithIssed(int $bookId, int $userId) 
    {
        $issuedStatus = BookModel::ISSUED_STATUS;
        $getBookStmt = $this->conn->prepare("
            select b.*, g.genre as genreName, l.name as langName, rq.status as request_status, rq.requester_id, r.user_name as ownerName
            from books as b
            inner join request as rq on b.id = rq.book_id
            inner join genre as g on g.id = b.genre
            inner join language as l on l.id = b.book_lang
            inner join register as r on r.id = b.owner_id
            where b.id = ? and rq.status = ?
        ");
        $getBookStmt->bind_param("ii", $bookId, $issuedStatus);
        $getBookStmt->execute();
        $getRst = $getBookStmt->get_result();
        if ($getRst->num_rows > 0) {
            $book = $getRst->fetch_assoc();
            $book = $this->addBookCondition($book);
            $book = $this->getSimilarBookGenre($book);
            $book['isReviewedByUser'] = $this->checkBookReviewedByUser($bookId, $userId);
            return $book;
        } else {
            return false;
        }
    }

    public function getBookDetailWithoutIssued(int $bookId, int $userId)
    {
        $getBookStmt = $this->conn->prepare("
            select b.*, g.genre as genreName, l.name as langName, r.user_name as ownerName
            from books as b
            inner join language as l on l.id = b.book_lang
            inner join genre as g on g.id = b.genre
            inner join register as r on r.id = b.owner_id
            where b.id = ?
        ");
        $getBookStmt->bind_param("i", $bookId);
        $getBookStmt->execute();
        $getRst = $getBookStmt->get_result();
        if ($getRst->num_rows > 0) {
            $book = $getRst->fetch_assoc();
            $book['request_status'] = null;
            $book['requester_id'] = null;
            $book = $this->addBookCondition($book);
            $book = $this->getSimilarBookGenre($book);
            $book['isReviewedByUser'] = $this->checkBookReviewedByUser($bookId, $userId);
            return $book;
        } else {
            return false;
        }
    }

    public function checkBookReviewedByUser(int $bookId, int $userId) : int
    {
        $checkBookReviewedStmt = $this->conn->prepare("select id from feedback where user_id = ? and book_id = ?");
        $checkBookReviewedStmt->bind_param("ii", $userId, $bookId);
        $checkBookReviewedStmt->execute();
        $result = $checkBookReviewedStmt->get_result()->fetch_assoc();
        if (is_array($result) && count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getSimilarBookGenre(array $bookDetail)
    {
        $getSimilarBookStmt = $this->conn->prepare("SELECT g.genre
        from books as b
        INNER JOIN genre as g on b.genre = g.id
        where b.book_name = ? and b.author = ? and b.publisher = ? and b.book_lang = ?");
        $getSimilarBookStmt->bind_param("sssi", $bookDetail['book_name'], $bookDetail['author'], $bookDetail['publisher'], $bookDetail['book_lang']);
        $getSimilarBookStmt->execute();
        $similarResult = $getSimilarBookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $generNameArray = [];
        foreach ($similarResult as $sBook) {
            if (!in_array($sBook['genre'], $generNameArray)) {
                $generNameArray[] = $sBook['genre'];
            }
        }
        $bookDetail['gNames'] = implode(", ",$generNameArray);
        return $bookDetail;
    }

    public function addBookCondition(array $book) : array
    {
        foreach ($book as $key => $val) {
            if ($key == 'book_condition') {
                if ($val == 0) {
                    $book['bookConditionName'] = "Bad";
                } elseif ($val == 1) {
                    $book['bookConditionName'] = "Good";
                } else {
                    $book['bookConditionName'] = 'Excellent';
                }
            }
        }
        return $book;
    }
    

    public function reviewList(int $bookId)
    {
        $getReviewStmt = $this->conn->prepare("
            select f.id as reviewId, f.feedback as review, f.user_id as userId, f.book_id as bookId, f.book_rating as rating, r.user_name as commenter_name, r.image as commenterImage, f.timestamp
            from feedback as f
            inner join register as r on f.user_id = r.id
            where f.book_id = ?"
        );
        $getReviewStmt->bind_param("i", $bookId);
        $getReviewStmt->execute();
        $getReviewRst = $getReviewStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $getReviewRst;
    }

    public function deleteFeed(int $feedId, int $userId, int $bookId)
    {
        $deleteFeedStmt = $this->conn->prepare("delete from feedback where id = ? and user_id = ? and book_id = ?");
        $deleteFeedStmt->bind_param("iii", $feedId, $userId, $bookId);
        $deleteFeedStmt->execute();

        if ($deleteFeedStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function borrowHistory(int $userId) : array
    {
        $requestedStatus = BookModel::REQUESTED_STATUS;
        $borrowHistoryStmt = $this->conn->prepare("SELECT b.*, g.genre, r.status as rstatus, r.rqst_date, r.issued_date, r.return_date, r.reason, rg.user_name
        FROM request as r
        INNER JOIN books as b on b.id = r.book_id 
        INNER JOIN genre as g on g.id = b.genre
        INNER JOIN register as rg on rg.id = r.owner_id
        WHERE r.requester_id = ? and (r.status != ? )
        ORDER BY r.issued_date");
        $borrowHistoryStmt->bind_param("ii", $userId, $requestedStatus);
        $borrowHistoryStmt->execute();
        $borrowHistoryData = $borrowHistoryStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $borrowHistoryData;
    }

    public function lendingHistory(int $userId) : array
    {
        $lendingHistoryStmt = $this->conn->prepare("SELECT b.*, g.genre, r.status as rstatus, r.rqst_date, r.issued_date, r.return_date, r.reason, rg.user_name
        FROM request as r
        INNER JOIN books as b on b.id = r.book_id 
        INNER JOIN genre as g on g.id = b.genre
        INNER JOIN register as rg on rg.id = r.requester_id
         WHERE r.owner_id = ? ORDER BY r.issued_date");
        $lendingHistoryStmt->bind_param("i", $userId);
        $lendingHistoryStmt->execute();
        $lendingHistoryData = $lendingHistoryStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $lendingHistoryData;
    }

    public function addToWishList(int $bookId, int $userId) : bool
    {
        $addToWishListStmt = $this->conn->prepare("insert into wish_list (book_id, user_id) values(?, ?)");
        $addToWishListStmt->bind_param("ii", $bookId, $userId);
        $addToWishListStmt->execute();
        if ($addToWishListStmt->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isAddedToWishList(int $bookId, int $userId) : bool
    {
        $checkStmt = $this->conn->prepare("select * from wish_list where book_id = ? and user_id = ?");
        $checkStmt->bind_param("ii", $bookId, $userId);
        $checkStmt->execute();
        $rst = $checkStmt->get_result();
        if ($rst->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFromWishList($bookId, $userId) : bool
    {
        $isAdded = $this->isAddedToWishList($bookId, $userId);
        if ($isAdded) {
            $removeFromWishListStmt = $this->conn->prepare("delete from wish_list where book_id = ? and user_id = ?");
            $removeFromWishListStmt->bind_param("ii", $bookId, $userId);
            $removeFromWishListStmt->execute();
            if ($removeFromWishListStmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function userFavBookList(int $userId) : array
    {
        $bookList = [];
        $userFavStmt = $this->conn->prepare("select * from wish_list where user_id = ?");
        $userFavStmt->bind_param("i", $userId);
        $userFavStmt->execute();
        $userFavRst = $userFavStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($userFavRst) > 0) {
            foreach ($userFavRst as $detail) {
                array_push($bookList, $detail['book_id']);
            }
            return $bookList;
        } else {
            return $bookList;
        }
    }

    public function showWishlistBooks(int $userId) : array
    {
        $favBookList = $this->userFavBookList($userId);
        if (!empty($favBookList)) {
            $booksIds = implode(',', $favBookList);
            $favBooksStmt = $this->conn->prepare("
                select b.*, g.genre
                from books as b
                inner join genre as g on g.id = b.genre
                where b.id in ($booksIds)
            ");
            $favBooksStmt->execute();
            $favBooksRst = $favBooksStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            return $favBooksRst;
        } else {
            return $favBookList;
        }
    }

    public function isBookWishListedByAnyone(int $bookId, array $bookDetail) : array
    {

        $isBookWishListedByAnyoneStmt = $this->conn->prepare("select * from wish_list where book_id = ?");
        $isBookWishListedByAnyoneStmt->bind_param("i", $bookId);
        $isBookWishListedByAnyoneStmt->execute();
        $rst = $isBookWishListedByAnyoneStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($rst)) {
            $bookDetail['isWishlist'] = 1;
        } else {
            $bookDetail['isWishlist'] = 0;
        }
        return $bookDetail;
    }

    public function getBooksByGenre(string $genreName) : array
    {
        $genreBookStmt = $this->conn->prepare("
            select b.*, g.genre
            from books as b
            inner join genre as g on b.genre = g.id
            where g.genre = ? 
        ");
        $genreBookStmt->bind_param("s", $genreName);
        $genreBookStmt->execute();
        $result = $genreBookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function getBookEditions(string $bookName) : array
    {
        $editions = [];
        $getEditionStmt = $this->conn->prepare("select DISTINCT edition
            from books
            where book_name = ?
            order by edition asc"
        );
        $getEditionStmt->bind_param("s", $bookName);
        $getEditionStmt->execute();
        $editions = $getEditionStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $editions;
    }

    public function checkGenreExist(string $bGenre) : bool
    {
        $checkGenreExistStmt = $this->conn->prepare("select * from genre where genre = ?");
        $checkGenreExistStmt->bind_param("s", $bGenre);
        $checkGenreExistStmt->execute();
        $result = $checkGenreExistStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getGenerId(string $bGenre) : int
    {
        $getGenerIdStmt = $this->conn->prepare("select id from genre where genre = ?");
        $getGenerIdStmt->bind_param("s", $bGenre);
        $getGenerIdStmt->execute();
        $result = $getGenerIdStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result[0]['id'];
    }

    public function insertNewGenre(string $bGenre) : int
    {
        $insertNewGenreStmt = $this->conn->prepare("insert into genre(genre) values(?)");
        $insertNewGenreStmt->bind_param("s", $bGenre);
        $insertNewGenreStmt->execute();
        return $insertNewGenreStmt->insert_id;
    }

    public function sameBookBysameUser(
        $user_id,
        $bName,
        $bGenre, 
        $bookLang,
        $bAuthor,
        $edition,
        $publisher,
        $ISBN
    ) : bool {
        $checkSameBookStmt = $this->conn->prepare("select * from books where owner_id = ? and book_name = ? and genre = ? and book_lang = ? and author = ? and edition = ? and publisher = ? and isbn = ?");
        $checkSameBookStmt->bind_param("sssssiss", $user_id, $bName, $bGenre, $bookLang, $bAuthor, $edition, $publisher, $ISBN);
        $checkSameBookStmt->execute();
        $result = $checkSameBookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function checkLangExists(string $languageName) : bool
    {
        $checkLangExistStmt = $this->conn->prepare("select * from language where name = ?");
        $checkLangExistStmt->bind_param("s", $languageName);
        $checkLangExistStmt->execute();
        $result = $checkLangExistStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLanguageCode(string $languageName, string $languageCode) : bool
    {
        $updateLangCodeStmt = $this->conn->prepare("update language set lang_code = ? where name = ?");
        $updateLangCodeStmt->bind_param("ss", $languageCode, $languageName);
        $updateLangCodeStmt->execute();
        return true;
    }

    public function getLangId(string $languageName,string $languageCode) : int
    {
        $this->updateLanguageCode($languageName,$languageCode);       
        $getLangIdStmt = $this->conn->prepare("select id from language where name = ?");
        $getLangIdStmt->bind_param("s", $languageName);
        $getLangIdStmt->execute();
        $result = $getLangIdStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result[0]['id'];
    
    }

    public function insertNewLang(string $languageName, string $languageCode) : int
    {
        $insertNewGenreStmt = $this->conn->prepare("insert into language(name, lang_code) values(?, ?)");
        $insertNewGenreStmt->bind_param("ss", $languageName, $languageCode);
        $insertNewGenreStmt->execute();
        return $insertNewGenreStmt->insert_id;
    }

    public function getOwnerId(string $bookName, string $author, string $publisher, string $isbn) : array
    {
        $ownerIds = [];
        $getOwnerIdStmt = $this->conn->prepare("select owner_id
        from books
        where book_name = ? and author = ? and publisher = ? and isbn = ?");
        $getOwnerIdStmt->bind_param("ssss", $bookName, $author, $publisher, $isbn);
        $getOwnerIdStmt->execute();
        $rst = $getOwnerIdStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($rst as $r) {
            $ownerIds[] = $r['owner_id'];
        }
        return $ownerIds;

    }

    public function getUserAndBookDetails(int $userId, int $ownerId, string $bookName, string $author, string $publisher, string $isbn) : array
    {
        $getUserDetailStmt = $this->conn->prepare("select id as ownerId, image, user_name as ownerName, mobile_no as phoneNumber, address as ownerAddress
        from register
        where id = ?");
        $getUserDetailStmt->bind_param("i", $ownerId);
        $getUserDetailStmt->execute();
        $rst = $getUserDetailStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $userDetail = $this->formatResult($rst, $userId, $ownerId, $bookName, $author, $publisher, $isbn);
        return $userDetail;
    }
    
    public function formatResult(array $rst, int $userId, int $ownerId, string $bookName, string $author, string $publisher, string $isbn): array
    {
        $ownerBookDetail = [];
        $books = [];
        $ownerBookDetail['ownerId'] = $rst[0]['ownerId'];
        $ownerBookDetail['ownerName'] = $rst[0]['ownerName'];
        $ownerBookDetail['ownerImage'] = $rst[0]['image'];
        $ownerBookDetail['phoneNumber'] = $rst[0]['phoneNumber'];
        $ownerBookDetail['ownerAddress'] = $rst[0]['ownerAddress'];
        $ownerBookDetail['description'] = $this->getBookDescription($ownerId, $bookName, $author, $publisher, $isbn);
        $ownerBookDetail['isBlocked'] = 0;
        $bookDetails = $this->getBookDetails($ownerId, $bookName, $author, $publisher, $isbn);
        foreach ($bookDetails as $book) {
            $bookDetail = [];
            $bookDetail['bookId']= $book['bookId'];
            $bookDetail['requestStatus']= $book['requestStatus'];
            $bookDetail['edition']= $book['edition'];
            if ($book['bookCondition'] == 0) {
                $bookDetail['bookCondition']= "Bad";
            } elseif ($book['bookCondition'] == 1) {
                $bookDetail['bookCondition']= "Good";
            } elseif ($book['bookCondition'] == 2) {
                $bookDetail['bookCondition']= "Excellent";
            } else {
                $bookDetail['bookCondition']= "Unkown";
            }
            $bookDetail['isRequested'] = $this->getIsRequested($userId,$book['bookId'],$ownerId);
            $books[] = $bookDetail;
        }
        $ownerBookDetail['ownerBookInfo'] = $books;
        return $ownerBookDetail;
    }

    public function getBookDescription(int $ownerId, string $bookName, string $author, string $publisher, string $isbn) : string
    {
        $getBookDescriptionStmt = $this->conn->prepare("
            select description
            from books
            where owner_id = ? and book_name = ? and author = ? and publisher = ? and isbn = ?
            order by upload_date desc
        ");
        $getBookDescriptionStmt->bind_param("issss", $ownerId, $bookName, $author, $publisher, $isbn);
        $getBookDescriptionStmt->execute();
        $result = $getBookDescriptionStmt->get_result()->fetch_assoc();
        return $result['description'];
    }

    public function getBookDetails($ownerId, $bookName, $author, $publisher, $isbn) : array
    {
        $sql = $this->conn->prepare("select id as bookId, book_status as requestStatus, edition, book_condition as bookCondition
        from books 
        where book_name = ? and author = ? and publisher = ? and isbn = ? and owner_id = ?");
        $sql->bind_param("ssssi", $bookName, $author, $publisher, $isbn, $ownerId);
        $sql->execute();
        $rst = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $rst;
    }

    public function getIsRequested(int $userId, int $bookId, int $ownerId) : int
    {
        $requestedStatus = BookModel::REQUESTED_STATUS;
        $sql = $this->conn->prepare("
            select id
            from request
            where (requester_id = ? and owner_id = ? and book_id = ? and status = ?)
        ");
        $sql->bind_param("iiii", $userId, $ownerId, $bookId, $requestedStatus);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($result);
    }

    public function formateEditionCondition(array $books) : array
    {
        $updatedBookDetails = [];
        foreach ($books as $book) {
            $bookDetail = [];
            $editionCondition = [];
            $bookDetail['bookId'] = $book['bookId'];
            $bookDetail['ownerId'] = $book['ownerId'];
            $bookDetail['requestStatus'] = $book['requestStatus'];
            $bookDetail['userName'] = $book['userName'];
            $bookDetail['userAddress'] = $book['userAddress'];
            $editionCondition['edition'] = $book['edition'];
            $editionCondition['bookCondition'] = $book['bookCondition'];
            $bookDetail['editionCondition'] = $editionCondition;
            $updatedBookDetails[] = $bookDetail;
        }
        return $updatedBookDetails;
    }

    public function alreadyAddedReview(int $bookId, int $userId) : bool
    {
        $alreadyAddedReview = $this->conn->prepare("
            select * from feedback where user_id = ? and book_id = ?
        ");
        $alreadyAddedReview->bind_param("ii", $userId, $bookId);
        $alreadyAddedReview->execute();
        $result = $alreadyAddedReview->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchBooks(int $isAuthor, string $search, int $languageId, int $genreId)
    {
        $sql = "select b.id as bookId, b.image as bookThumbnail,b.isbn,b.owner_id as ownerId, b.book_name as title, b.author, b.book_lang as languageId, l.name as language, b.genre as genreId, g.genre, b.rating, b.review
        from books as b
        inner join language as l on b.book_lang = l.id
        inner join genre as g on b.genre = g.id";

        if (isset($isAuthor) && $isAuthor == 1) {
            $sql .= " where b.author = ?";
            $params = [$search];
            $dataTypes = "s";
        } else {
            $sql .= " where (b.book_name Like ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $val = "%$search%";
            $params = [$val, $val, $val];
            $dataTypes = "sss";
        }

        if (isset($genreId) && !empty($genreId)) {
            $sql .= " and (b.genre = ?)";
            $params[] = $genreId;
            $dataTypes .= "i";
        }
        if (isset($languageId) && !empty($languageId)) {
            $sql .= " and (b.book_lang = ?)";
            $params[] = $languageId;
            $dataTypes .= "i";
        }
        $sql .= " ORDER BY b.upload_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($dataTypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function makeBookListUniqueByBookName(array $books)
    {
        $updateBookList = [];
        $bookTitle = [];
        foreach ($books as $book) {
            if (!in_array($book['title'], $bookTitle)) {
                $bookTitle[] = $book['title'];
                $updateBookList[] = $book;
            }
        }
        return $updateBookList;
    }

    public function isBookWishListed(int $bookId) : int
    {
        $isBookWishListed = $this->conn->prepare("select * from wish_list where book_id = ?");
        $isBookWishListed->bind_param("i", $bookId);
        $isBookWishListed->execute();
        $rst = $isBookWishListed->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($rst);
    }

    public function getNoOfReviews(int $bookId) : int
    {
        $getNoOfReviewsSTMT = $this->conn->prepare("select id from feedback where book_id = ?");
        $getNoOfReviewsSTMT->bind_param("i", $bookId);
        $getNoOfReviewsSTMT->execute();
        $rst = $getNoOfReviewsSTMT->get_result()->fetch_all(MYSQLI_ASSOC);     
        return count($rst);
    }

    public function getSharedNoOfUsers(
        string $bookName,
        string $author,
        int $langId,
        int $genreId,
        string $isbn
    ) : int {
        $getSharedNoOfUsersStmt = $this->conn->prepare("select id from books where book_name = ? and author = ? and book_lang = ? and genre = ? and isbn = ?");
        $getSharedNoOfUsersStmt->bind_param("ssiis", $bookName, $author, $langId, $genreId, $isbn);
        $getSharedNoOfUsersStmt->execute();
        $rst = $getSharedNoOfUsersStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return count($rst);
    }

    public function getUserReview(int $userId, int $bookId) : array
    {
        $getUserReviewStmt = $this->conn->prepare("SELECT feedback, book_rating from feedback where user_id = ? and book_id = ?");
        $getUserReviewStmt->bind_param("ii", $userId, $bookId);
        $getUserReviewStmt->execute();
        $reviewResult = $getUserReviewStmt->get_result()->fetch_assoc();
        if (is_array($reviewResult) && count($reviewResult) > 0) {
            return [$reviewResult['feedback'], $reviewResult['book_rating']];
        } else {
            return [];
        }
    }
}