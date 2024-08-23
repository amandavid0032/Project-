<?php
namespace App\Model;

class RequestModel
{
    //for request table
    public const REQUESTED_STATUS = 0;
    public const APPROVE_STATUS = 1;
    public const RETURNING_STATUS = 2;
    public const RETURN_REQUEST_ACCEPTED = 3;
    public const REJECTED_STATUS = 4;
    public const ISSUED_DATE = "0000-00-00";
    public const RETURN_DATE = "0000-00-00";
    //for books table
    public const ISSUED_STATUS = 1;
    public const AVAILABLE_STATUS = 0;
    public const BORROWER_CANCEL_STATUS = 5;
    public const FINALLY_BOOK_RETURNED = 6;

    
    protected $conn;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkBookBelongsToOwner(int $bookId, int $bookOwner): bool
    {
        $checkBookBelongsToOwner = $this->conn->prepare("
        SELECT 1 FROM books WHERE id = ? AND owner_id = ?
        UNION
        SELECT 1 FROM book_copies WHERE book_id = ? AND owner_id = ?
    ");
        $checkBookBelongsToOwner->bind_param("iiii", $bookId, $bookOwner, $bookId, $bookOwner);
        $checkBookBelongsToOwner->execute();
        $checkBookBelongsToOwnerRst = $checkBookBelongsToOwner->get_result();
        $exists = $checkBookBelongsToOwnerRst->num_rows > 0;
        $checkBookBelongsToOwner->close();

        return $exists;
    }



    public function RequestBook(int $bookId, int $requesterId, int $ownerId, string $date) : bool
    {
        $status = RequestModel::REQUESTED_STATUS;
        $issued_date = RequestModel::ISSUED_DATE;
        $return_date = RequestModel::RETURN_DATE;
        $alreadyRequested = $this->checkAlreadyRequested($requesterId, $ownerId, $bookId);
        if (!$alreadyRequested) {
            $insertRqst = $this->conn->prepare("insert ignore into request(requester_id, owner_id, book_id, status, rqst_date, issued_date, return_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertRqst->bind_param("iiiisss", $requesterId, $ownerId, $bookId, $status, $date, $issued_date, $return_date);
            $insertRqst->execute();
            if ($insertRqst->insert_id > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function checkAlreadyRequested(int $requesterId, int $ownerId,int $bookId)
    {
        $pendingStatus = RequestModel::REQUESTED_STATUS;
        $issuedStatus = RequestModel::APPROVE_STATUS;
        $returningStatus = RequestModel::RETURNING_STATUS;
        $checkStmt = $this->conn->prepare("select * from request where requester_id = ? and owner_id = ? and book_id = ? and (status = ? or status = ? or status = ?)");
        $checkStmt->bind_param("iiiiii", $requesterId, $ownerId, $bookId, $pendingStatus, $issuedStatus, $returningStatus);
        $checkStmt->execute();
        $checkStmtRows = $checkStmt->get_result();
        if ($checkStmtRows->num_rows > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function listRequests(int $userId) : array
    {
        $pendingStatus = RequestModel::REQUESTED_STATUS;
        $returningStatus = RequestModel::RETURNING_STATUS;
        $listRqst = $this->conn->prepare("select rg.user_name as requester_name, bo.book_name as book_name, rq.status, rq.requester_id, rq.book_id
        from request as rq
        inner join register as rg on rg.id = rq.requester_id
        inner join books as bo on bo.id = rq.book_id
        where rq.owner_id = ? and (rq.status = ? or rq.status = ?)");
        $listRqst->bind_param("iii", $userId, $pendingStatus, $returningStatus);
        $listRqst->execute();
        $myRequests = $listRqst->get_result()->fetch_all(MYSQLI_ASSOC);
        return $myRequests;
    }

    public function formateReceivedRqstMessage($listRequest)
    {
        $formatedList = [];
        $arr = [];
        foreach($listRequest as $item) {
            if ($item['status'] == 2) {
                $arr['book_id'] = $item['book_id'];
                $arr['requester_id'] = $item['requester_id'];
                $arr['Rqst_status'] = $item['status'];
                $msg = $item['requester_name']." wants to return your ".$item['book_name']." book";
                $arr['message'] = $msg;
                $msg = '';
                array_push($formatedList, $arr);
                $arr = [];
            } else if ($item['status'] == 0) {
                $arr['book_id'] = $item['book_id'];
                $arr['requester_id'] = $item['requester_id'];
                $arr['Rqst_status'] = $item['status'];
                $msg = $item['requester_name']." requested you ".$item['book_name']." book";
                $arr['message'] = $msg;
                $msg = '';
                array_push($formatedList, $arr);
                $arr = [];
            }
        }
        return $formatedList;
    }

    public function listSentRequest(int $userId) : array
    {
        $pedingStatus = RequestModel::REQUESTED_STATUS;
        $returningStatus = RequestModel::RETURNING_STATUS;
        $listSentRqst = [];
        $listRqstSent = $this->conn->prepare("select rg.user_name as owner, bo.book_name as book, rq.status, rq.owner_id, rq.book_id
        from request as rq
        inner join register as rg on rg.id = rq.owner_id
        inner join books as bo on bo.id = rq.book_id
        where rq.requester_id = ? and (rq.status = ? or rq.status = ?)");
        $listRqstSent->bind_param("iii", $userId, $pedingStatus, $returningStatus);
        $listRqstSent->execute();
        $listSentRqst = $listRqstSent->get_result()->fetch_all(MYSQLI_ASSOC);
        return $listSentRqst;
    }

    public function formateSentRqstMsg($listSentRst) : array
    {
        $sentRqstList = [];
        $arr = [];
        foreach($listSentRst as $item) {
            if ($item['status'] == 0) {
                $arr['book_id'] = $item['book_id'];
                $arr['owner_id'] = $item['owner_id'];
                $arr['status'] = $item['status'];
                $msg = "You requested ".$item['book']." to ".$item['owner'];
                $arr['message'] = $msg;
                array_push($sentRqstList, $arr);
                $msg = '';
                $arr = [];
            } else if ($item['status'] == 2) {
                $arr['book_id'] = $item['book_id'];
                $arr['owner_id'] = $item['owner_id'];
                $arr['status'] = $item['status'];
                $msg = "You are returning ".$item['book']." to ".$item['owner'];
                $arr['message'] = $msg;
                array_push($sentRqstList, $arr);
                $msg = '';
                $arr = [];
            }
        }
        return $sentRqstList;
    }

    public function isBookAvailable(int $bookId): bool
    {
        $isBookAvailableStmt = $this->conn->prepare("
        SELECT book_status FROM books WHERE id = ?
        UNION ALL
        SELECT book_status FROM book_copies WHERE book_id = ?
    ");
        $isBookAvailableStmt->bind_param("ii", $bookId, $bookId);
        $isBookAvailableStmt->execute();
        $isBookAvailableRst = $isBookAvailableStmt->get_result();

        while ($row = $isBookAvailableRst->fetch_assoc()) {
            if ($row['book_status'] == 0) {
                return true;
            }
        }
        return false;
    }

    public function grantIssueRequest(int $requesterId, int $ownerId, int $bookId, string $dueDate, string $date): bool
    {
        $approveRequest = RequestModel::APPROVE_STATUS;
        $issuedStatus = RequestModel::ISSUED_STATUS;

        // Check if it's an original book or a copy
        $bookCheckQry = $this->conn->prepare("
    SELECT 'books' as type FROM books WHERE id = ? AND owner_id = ?
    UNION ALL
    SELECT 'book_copies' as type FROM book_copies WHERE book_id = ? AND owner_id = ?
    ");
        $bookCheckQry->bind_param("iiii", $bookId, $ownerId, $bookId, $ownerId);
        $bookCheckQry->execute();
        $result = $bookCheckQry->get_result();

        if (
            $result->num_rows > 0
        ) {
            $row = $result->fetch_assoc();
            $type = $row['type'];
            if ($type == 'books') {
                $updateBookStatusIssuedStmt = $this->conn->prepare("UPDATE books SET book_status = ? WHERE id = ? AND owner_id = ?");
                $updateBookStatusIssuedStmt->bind_param("iii", $issuedStatus, $bookId, $ownerId);
                $updateBookStatusIssuedStmt->execute();
            } else {
                $updateBookStatusIssuedStmt = $this->conn->prepare("UPDATE book_copies SET book_status = ? WHERE book_id = ? AND owner_id = ?");
                $updateBookStatusIssuedStmt->bind_param("iii", $issuedStatus, $bookId, $ownerId);
                $updateBookStatusIssuedStmt->execute();
            }

            $grantIssueQry = $this->conn->prepare("UPDATE request SET status = ?, issued_date = ?, due_date = ? WHERE requester_id = ? AND owner_id = ? AND book_id = ?");
            $grantIssueQry->bind_param("issiii", $approveRequest, $date, $dueDate, $requesterId, $ownerId, $bookId);
            $grantIssueQry->execute();

            return $grantIssueQry->affected_rows > 0;
        }

        return false;
    }


    public function rejectIssueRequest(int $requesterId, int $ownerId, int $bookId, string $cancelReason): bool
    {
        $date = date("Y-m-d");
        $rejectStatus = RequestModel::REJECTED_STATUS;

        $cancelIssueRequest = $this->conn->prepare("UPDATE request SET status = ?, reason = ?, owner_reject_date = ? WHERE (requester_id = ? AND owner_id = ? AND book_id = ?)");
        $cancelIssueRequest->bind_param("issiii", $rejectStatus, $cancelReason, $date, $requesterId, $ownerId, $bookId);
        $cancelIssueRequest->execute();

        return $cancelIssueRequest->affected_rows > 0;
    }

    public function acceptReturnRequest(int $requesterId, int $bookId, int $ownerId): bool
    {
        $acceptReturnRequest = RequestModel::RETURN_REQUEST_ACCEPTED;

        $acceptReturnRequestQry = $this->conn->prepare("UPDATE request SET status = ? WHERE (requester_id = ? AND book_id = ? AND owner_id = ?)");
        $acceptReturnRequestQry->bind_param("iiii", $acceptReturnRequest, $requesterId, $bookId, $ownerId);
        $acceptReturnRequestQry->execute();

        return $acceptReturnRequestQry->affected_rows > 0;
    }

    public function returnBookRequest(int $requesterId, int $bookId) : bool
    {
        $returnValue = RequestModel::RETURNING_STATUS;
        $updateBookStatusReturningStmt = $this->conn->prepare("update books set book_status = ? where id = ?");
        $updateBookStatusReturningStmt->bind_param("ii", $returnValue, $bookId);
        $updateBookStatusReturningStmt->execute();//updating book_request to returning status.

        $returningBook = $this->conn->prepare("update request set status = ? where (requester_id = ? and book_id = ?)");
        $returningBook->bind_param("iii", $returnValue, $requesterId, $bookId);
        $returningBook->execute();//updating request status to returning status.
        if ($returningBook->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getRequests(int $bookId, int $requesterId)
    {
        $getRequestStmt = $this->conn->prepare("select * from request where book_id = ? and requester_id = ?");
        $getRequestStmt->bind_param("ii", $bookId, $requesterId);
        $getRequestStmt->execute();
        $rst = $getRequestStmt->get_result();
        if ($rst->num_rows > 0) {
            $getRequestRst = $rst->fetch_assoc();
            return $getRequestRst;
        } else {
            return false;
        }
    }

    public function requestCancellationByBorrower(
        int $requesterId,
        int $ownerId,
        int $bookId
    ) : bool
    {
        $date = Date("Y-m-d");
        $requestedStatus = RequestModel::REQUESTED_STATUS;
        $borrowerCancelStatus = RequestModel::BORROWER_CANCEL_STATUS;
        $cancelRequestStmt = $this->conn->prepare("
            UPDATE request set status = ?, borrower_cancel_date = ?
            WHERE (requester_id = ? and owner_id = ? and book_id = ? and status = ?)
        ");
        $cancelRequestStmt->bind_param("isiiii", $borrowerCancelStatus, $date, $requesterId, $ownerId, $bookId, $requestedStatus);
        $cancelRequestStmt->execute();
        if ($cancelRequestStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isReviewdByBorrowerAndBookConditionByBorrower(array $notification, int $userId)
    {
        $updatedArray = [];
        $borrowerReview = 0;//review given by borrower.
        $ownerReview = 1;//review given by owner.
        foreach ($notification as $noti) {
            $isReviewdStmt = $this->conn->prepare("select id, book_condition_by_borrower, book_condition_by_owner from book_review_history where owner_id = ? and book_id = ? and borrower_id = ? and commented_by = ?");
            $isReviewdStmt->bind_param("iiii", $noti['owner_id'], $noti['book_id'], $userId, $borrowerReview);
            $isReviewdStmt->execute();
            $result = $isReviewdStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $isReviewedByOwner = $this->isReviewedByOwner($noti['book_id'], $noti['owner_id'], $userId, $ownerReview);
            if (count($result) > 0) {
                $noti['isBookReviewedByBorrower'] = 1;
                $noti['isBookReviewedByOwner'] = $isReviewedByOwner;
                $noti['bookConditionByBorrower'] = $result[0]['book_condition_by_borrower'];
            } else {
                $noti['isBookReviewedByBorrower'] = 0;
                $noti['isBookReviewedByOwner'] = $isReviewedByOwner;
                $noti['bookConditionByBorrower'] = null;
            }
            $updatedArray[] = $noti;
        }
        return $updatedArray;
    }

    public function isReviewedByOwner(int $bookId, int $ownerId, $requesterId, $ownerReview) : int
    {
        $reviewByOwnerStmt = $this->conn->prepare("select id from book_review_history where owner_id = ? and book_id = ? and borrower_id = ? and commented_by = ?");
        $reviewByOwnerStmt->bind_param("iiii", $ownerId, $bookId, $requesterId, $ownerReview);
        $reviewByOwnerStmt->execute();
        $result = $reviewByOwnerStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getNotification(int $userId, string $columnName, string $notiType) : array
    {
        $requestStatus = 0;//reqeusted to the owner but not accepted yet.
        $returnStatus = 2;//requested to return the book.
        $issuedStatus = 1;//status for those whose books are issued.
        $acceptReturnRequestStatus = 3;//return book request accepted by the owner.
        $bookRequestRejectedByOwner = 4;//request rejected by the owner.
        $borrowerCancelRequest = 5;//Borrower cancels request by himself.
        $bookFinallyReturned = 6;//finally book returned to the owner.
        $notificationsList = [];
        $getNotificationStmt = "
            select r.*, b.book_name, b.image, b.book_condition as bookConditionByOwner, b.author, b.edition, rg.user_name as ownerName, reg.user_name as requesterName, rg.image as ownerImage, rg.address as ownerAddress, reg.image as requesterImage, reg.address as requesterAddress
            from request as r
            inner join books as b on b.id = r.book_id
            inner join register as rg on rg.id = r.owner_id
            inner join register as reg on reg.id = r.requester_id";
        if ($notiType == 'send' || $notiType == 'receive') {
            $getNotificationStmt .= " where ((r.$columnName = $userId) and (r.status = $requestStatus or r.status = $returnStatus or r.status = $issuedStatus or r.status = $acceptReturnRequestStatus))";
        } elseif ($notiType == 'borrow' || $notiType == 'lent') {
            $getNotificationStmt .= " where ((r.$columnName = $userId) and (r.status = $requestStatus or r.status = $returnStatus or r.status = $issuedStatus or r.status = $acceptReturnRequestStatus or r.status = $bookRequestRejectedByOwner or r.status = $borrowerCancelRequest or r.status = $bookFinallyReturned))";
        } elseif ($notiType == "notification") {
            $getNotificationStmt .= " where r.requester_id = $userId or r.owner_id = $userId";
        }
        $getNotificationStmt = $this->conn->prepare($getNotificationStmt);
        $getNotificationStmt->execute();
        $notificationsList = $getNotificationStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $notificationsList;
    }

    public function checkIfBookIssued(int $bookId, int $ownerId, int $userId)
    {
        $issuedStatus = RequestModel::ISSUED_STATUS;
        $returnRequestAccepted = RequestModel::RETURN_REQUEST_ACCEPTED;
        $checkIfBookIssuedToRespectedUser = $this->conn->prepare("select id 
        from request
        where (requester_id = ? and owner_id = ? and book_id = ?) and (status = ? or status = ?)");
        $checkIfBookIssuedToRespectedUser->bind_param("iiiii", $userId, $ownerId, $bookId, $issuedStatus, $returnRequestAccepted);
        $checkIfBookIssuedToRespectedUser->execute();
        $result = $checkIfBookIssuedToRespectedUser->get_result()->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertReview(
        int $bookId,
        int $ownerId,
        int $userId,
        int $bookConditionByBorrower,
        int $bookConditionByOwner,
        string $message,
        int $commentedBy
    ) : bool
    {
        $insertReviewStmt = $this->conn->prepare("INSERT INTO `book_review_history` (`book_condition_by_owner`, `book_condition_by_borrower`, `commented_by`, `owner_id`, `borrower_id`, `book_id`, `borrower_comment`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertReviewStmt->bind_param("iiiiiis", $bookConditionByOwner, $bookConditionByBorrower, $commentedBy, $ownerId, $userId, $bookId, $message);
        $insertReviewStmt->execute();
        if ($insertReviewStmt->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateBookStatusForReturning(int $bookId, int $userId, int $ownerId, string $returningDate)
    {
        $returningStatus = RequestModel::RETURNING_STATUS;
        $updateBookStatusReturningStmt = $this->conn->prepare("
        UPDATE request
        set status = ?, returning_date = ?
        where book_id = ? and owner_id = ? and requester_id = ?");
        $updateBookStatusReturningStmt->bind_param("isiii", $returningStatus, $returningDate, $bookId, $ownerId, $userId);
        $updateBookStatusReturningStmt->execute();
        if ($updateBookStatusReturningStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStatusInBooksTable(int $bookId, string $type)
    {
        $status = $this->determineStatus($type);
        $bookUpdated = $this->updateBookStatus($bookId, $status);
        $copiesUpdated = $this->updateCopiesStatus($bookId, $status);
        return $bookUpdated || $copiesUpdated;
    }

    private function determineStatus(string $type): ?int
    {
        if ($type == 'returning') {
            return RequestModel::RETURNING_STATUS;
        } elseif ($type == 'returned') {
            return RequestModel::AVAILABLE_STATUS;
        }
        return null; 
    }

    private function updateBookStatus(int $bookId, int $status): bool
    {
        $updateBookStmt = $this->conn->prepare("UPDATE books SET book_status = ? WHERE id = ?");
        $updateBookStmt->bind_param("ii", $status, $bookId);
        $updateBookStmt->execute();
        return $updateBookStmt->affected_rows > 0;
    }

    private function updateCopiesStatus(int $bookId, int $status): bool
    {
        $updateCopiesStmt = $this->conn->prepare("UPDATE book_copies SET book_status = ? WHERE book_id = ?");
        $updateCopiesStmt->bind_param("ii", $status, $bookId);
        $updateCopiesStmt->execute();
        return $updateCopiesStmt->affected_rows > 0;
    }

    public function updateBookStatusForFinalReturn($bookId, $ownerId, $requesterId)
    {
        $returnedDate = Date("Y-m-d");
        $returnedStatus = RequestModel::FINALLY_BOOK_RETURNED;
        $updateBookStatusReturnedStmt = $this->conn->prepare("
        UPDATE request
        set status = ?, return_date = ?
        where book_id = ? and owner_id = ? and requester_id = ?");
        $updateBookStatusReturnedStmt->bind_param("isiii", $returnedStatus, $returnedDate, $bookId, $ownerId, $requesterId);
        $updateBookStatusReturnedStmt->execute();
        if ($updateBookStatusReturnedStmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllNotification(int $userId) : array
    {
        $notificationStmt = $this->conn->prepare("select * from notification where receiver_id = ? order by notification_date desc");
        $notificationStmt->bind_param("i", $userId);
        $notificationStmt->execute();
        $notifList = $notificationStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $notifList;
    }

}