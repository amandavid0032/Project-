<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\GetTokenFromDb\GetToken;
use App\Token\GenToken;
use App\Send_Notification\Notification;


class RequestController
{
    protected $conn;
    protected $requestModelObj;
    protected $valToken;
    protected $token;
    protected $notificationObj;

    public const STATUS_RETURNED = "returned";
    public const STATUS_RETURNING = "returning";
    public function __construct($requestModelObj, $notificationObj, $conn)
    {
        $this->conn = $conn;
        $this->requestModelObj = $requestModelObj;
        $this->valToken = new GetToken($conn);
        $this->token = new GenToken();
        $this->notificationObj = new Notification($conn);
    }

    public function requestForBook(Request $request, Response $response)
    {
        $reqst_date = date("Y-m-d");
        $params = $request->getParsedBody();
        $requesterId = (int)trim($params['userId'] ?? '');
        $bookOwner = (int)trim($params['ownerId'] ?? '');
        $bookId = (int)trim($params['bookId'] ?? '');
        $isRequest = (int)trim($params['isRequest'] ?? '');
        $edition = (int)trim($params['edition'] ?? '');
        $sendingNewBookRequest = 0; // sending new book request
        $cancellingBookSentRequest = 5; // borrower cancels sent book request by himself

        if ($isRequest == 1) { // request to borrow the book
            $isBookBelongsToOwner = $this->requestModelObj->checkBookBelongsToOwner($bookId, $bookOwner);
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "This Book doesn't belong to the specified Owner."
            ];
            if ($isBookBelongsToOwner) {
                $rqstBookRst = $this->requestModelObj->RequestBook($bookId, $requesterId, $bookOwner, $reqst_date);
                if ($rqstBookRst) {
                    $this->notificationObj->sendNoti($bookOwner, $requesterId, $bookId, $sendingNewBookRequest);
                    $jsonMessage = [
                        "isSuccess" => true,
                        "message" => "Request sent successfully for this book."
                    ];
                } else {
                    $jsonMessage = [
                        "isSuccess" => false,
                        "message" => "Already requested for this book."
                    ];
                }
            }
        } elseif ($isRequest == 0) { // borrower cancellation of the book request by himself
            $isBookBelongsToOwner = $this->requestModelObj->checkBookBelongsToOwner($bookId, $bookOwner);
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "This Book doesn't belong to the specified Owner."
            ];
            if ($isBookBelongsToOwner) {
                $returnRst = $this->requestModelObj->requestCancellationByBorrower($requesterId, $bookOwner, $bookId);
                $this->notificationObj->sendNoti($bookOwner, $requesterId, $bookId, $cancellingBookSentRequest);
                if ($returnRst) {
                    $jsonMessage = [
                        "isSuccess" => true,
                        "message" => "Book request cancelled successfully."
                    ];
                } else {
                    $jsonMessage = [
                        "isSuccess" => false,
                        "message" => "Already cancelled request."
                    ];
                }
            }
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
    }



    public function listReceivedRequest(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];
        //getting all the request done by the users.
        $listRequest = $this->requestModelObj->listRequests($userId);
        if(count($listRequest) > 0) {
            // function to formate the message in profer form to display.
            $formatedMessage = $this->requestModelObj->formateReceivedRqstMessage($listRequest);
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of all requests",
                "request" => $formatedMessage
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        } else {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "No new request received."
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(401);
        }
    }

    public function listSentRequest(Request $request, Response $response, $args)
    {
        $userId = (int)$args['userId'];   
        $listSentRst = $this->requestModelObj->listSentRequest($userId);
        if(count($listSentRst) > 0){
            $formatedMessage = $this->requestModelObj->formateSentRqstMsg($listSentRst);
            $jsonMessage = [
                "isSuccess" => true,            
                "message" => "List of request sent by you.",           
                "requests" => $formatedMessage
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        } else {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "No requests done till now.",
                "requests" => null
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }
    }
    public function accept_reject_request(Request $request, Response $response)
    {
        $date = date("Y-m-d");
        $params = $request->getParsedBody();
        $userId = (int)trim($params['userId'] ?? '');
        $bookId = (int)trim($params['bookId'] ?? '');
        $requesterId = (int)trim($params['requesterId'] ?? '');
        $ownerId = (int)trim($params['ownerId'] ?? '');
        $reason = trim($params['reason'] ?? '');
        $isAccept = trim($params['isAccept'] ?? ''); // 1: to accept the new book request, 0: to reject new book request, 2: to accept returning book request.
        $edition = trim($params['edition'] ?? '');
        $dueDate = trim($params['dueDate'] ?? '');
        $acceptBookRequestByOwner = 1; // owner accepts the book request
        $rejectBookRequestByOwner = 4; // owner rejects the book request
        $acceptsReturnBookRequestByOwner = 3; // owner accepts return book request
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "Bad request."
        ];
        if ($isAccept == 1 && empty($reason)) {
            $jsonMessage = $this->handleAcceptBookRequest($requesterId, $ownerId, $bookId, $dueDate, $date, $acceptBookRequestByOwner);
        } elseif ($isAccept == 0 && $reason) {
            $jsonMessage = $this->handleRejectBookRequest($requesterId, $ownerId, $bookId, $reason, $rejectBookRequestByOwner);
        } elseif ($isAccept == 2 && empty($reason)) {
            $jsonMessage = $this->handleAcceptReturnRequest($requesterId, $bookId, $ownerId, $acceptsReturnBookRequestByOwner);
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response->withHeader("content-type", "application/json")->withStatus(200);
    }

    private function handleAcceptBookRequest($requesterId, $ownerId, $bookId, $dueDate, $date, $isAccept)
    {
        $isBookAvailable = $this->requestModelObj->isBookAvailable($bookId);
        if ($isBookAvailable) {
            $grantRequestResult = $this->requestModelObj->grantIssueRequest($requesterId, $ownerId, $bookId, $dueDate, $date);

            if ($grantRequestResult) {
                $notifSent = $this->notificationObj->sendNoti($requesterId, $ownerId, $bookId, $isAccept);
                if ($notifSent) {
                    return [
                        "isSuccess" => true,
                        "message" => "Book Issued Successfully. Notification sent."
                    ];
                } else {
                    return [
                        "isSuccess" => false,
                        "message" => "Book Issued Successfully. Notification failed."
                    ];
                }
            } else {
                return [
                    "isSuccess" => false,
                    "message" => "Book Already issued."
                ];
            }
        } else {
            return [
                "isSuccess" => false,
                "message" => "Book not available. Try after some time."
            ];
        }
    }

    private function handleRejectBookRequest($requesterId, $ownerId, $bookId, $reason, $rejectBookRequestByOwner)
    {
        $rejectIssueRequest = $this->requestModelObj->rejectIssueRequest($requesterId, $ownerId, $bookId, $reason);

        if ($rejectIssueRequest) {
            $notifSent = $this->notificationObj->sendNoti($requesterId, $ownerId, $bookId, $rejectBookRequestByOwner);
            if ($notifSent) {
                return [
                    "isSuccess" => true,
                    "message" => "Book rejected successfully. Notification sent."
                ];
            } else {
                return [
                    "isSuccess" => false,
                    "message" => "Book rejected successfully. Notification failed."
                ];
            }
        } else {
            return [
                "isSuccess" => false,
                "message" => "Already requested!"
            ];
        }
    }

    private function handleAcceptReturnRequest($requesterId, $bookId, $ownerId, $acceptsReturnBookRequestByOwner)
    {
        $acceptReturningBookRequest = $this->requestModelObj->acceptReturnRequest($requesterId, $bookId, $ownerId);

        if ($acceptReturningBookRequest) {
            $notifSent = $this->notificationObj->sendNoti($requesterId, $ownerId, $bookId, $acceptsReturnBookRequestByOwner);
            if ($notifSent) {
                return [
                    "isSuccess" => true,
                    "message" => "Book return request accepted. Notification sent."
                ];
            } else {
                return [
                    "isSuccess" => false,
                    "message" => "Book return request accepted. Notification failed."
                ];
            }
        } else {
            return [
                "isSuccess" => false,
                "message" => "Already accepted!"
            ];
        }
    }


    public function requestStatus(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['book_id'] ?? '');
        $requesterId = (int)trim($params['requester_id'] ?? '');
        $requested = $this->requestModelObj->getRequests($bookId, $requesterId);
        if ($requested) {
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "List of request.",
                "request" => $requested
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        } else {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "No request.",
                "request" => null
            ];
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }

    }

    public function notificationList(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $userId = (int)trim($params['userId'] ?? '');
        $notiType = trim($params['type'] ?? '');
        $jsonMessage = [
            "isSuccess" => false,
            "message" => "No notification found.",
            "notification" => []
        ];
        if ($notiType == 'send') {
            $columnName = "requester_id";
            $notification = $this->requestModelObj->getNotification($userId, $columnName, $notiType);
            $notification = array_reverse($this->requestModelObj->isReviewdByBorrowerAndBookConditionByBorrower($notification, $userId));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Notification list.",
                "notification" => $notification
            ];
        } elseif ($notiType == "receive") {
            $columnName = "owner_id";
            $notification = $this->requestModelObj->getNotification($userId, $columnName, $notiType);
            $notification = array_reverse($this->requestModelObj->isReviewdByBorrowerAndBookConditionByBorrower($notification, $userId));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Notification list.",
                "notification" => $notification
            ];
        } elseif ($notiType == "borrow") {
            $columnName = "requester_id";
            $notification = $this->requestModelObj->getNotification($userId, $columnName, $notiType);
            $notification = array_reverse($this->requestModelObj->isReviewdByBorrowerAndBookConditionByBorrower($notification, $userId));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Notification list.",
                "notification" => $notification
            ];
        } elseif ($notiType == "lent") {
            $columnName = "owner_id";
            $notification = $this->requestModelObj->getNotification($userId, $columnName, $notiType);
            $notification = array_reverse($this->requestModelObj->isReviewdByBorrowerAndBookConditionByBorrower($notification, $userId));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Notification list.",
                "notification" => $notification
            ];
        } elseif ($notiType == "notification") {
            $notification = $this->requestModelObj->getNotification($userId, "", $notiType);
            $notification = array_reverse($this->requestModelObj->isReviewdByBorrowerAndBookConditionByBorrower($notification, $userId));
            $jsonMessage = [
                "isSuccess" => true,
                "message" => "Notification list.",
                "notification" => $notification
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function book_review(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['bookId'] ?? '');
        $ownerId = (int)trim($params['ownerId'] ?? '');
        $userId = (int)trim($params['userId'] ?? '');
        $requesterId = (int)trim($params['requesterId'] ?? '');
        $bookConditionByBorrower = (int)trim($params['bookConditionByBorrower'] ?? '');
        $bookConditionByOwner = (int)trim($params['bookConditionByOwner'] ?? '');
        $message = trim($params['message'] ?? '');
        $commentedBy = trim($params['commentedBy'] ?? '');
        $reviewedByBorrower = 7;
        $reviewedByOwner = 6;

        $isBookIssuedToUser = $this->requestModelObj->checkIfBookIssued($bookId, $ownerId, $requesterId);
        if ($isBookIssuedToUser) {
            $addReviewForBook = $this->requestModelObj->insertReview($bookId, $ownerId, $requesterId, $bookConditionByBorrower, $bookConditionByOwner, $message, $commentedBy);
            if (isset($commentedBy) && $commentedBy == 1) {
                $this->requestModelObj->updateBookStatusForFinalReturn($bookId, $ownerId, $requesterId);
                $this->requestModelObj->updateStatusInBooksTable($bookId, RequestController::STATUS_RETURNED);
                $this->notificationObj->sendNoti($requesterId, $ownerId, $bookId, $reviewedByOwner);
            } else {
                $this->notificationObj->sendNoti($ownerId, $requesterId, $bookId, $reviewedByBorrower);
            }
            
            if ($addReviewForBook) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "Review Added Successfully."
                ];
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Unable to add review."
                ];
            }
        } else {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "Book not issued to particular user."
            ];
        }
        
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
    }

    public function returnBookByBorrower(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $bookId = (int)trim($params['bookId'] ?? '');
        $userId = (int)trim($params['userId'] ?? '');
        $ownerId = (int)trim($params['ownerId'] ?? '');
        $returningDate = trim($params['returningDate']);
        $returningBookRequest = 2;
        $isBookIssuedToUser = $this->requestModelObj->checkIfBookIssued($bookId, $ownerId, $userId);
        if ($isBookIssuedToUser) {
            $updateReturningStatus = $this->requestModelObj->updateBookStatusForReturning($bookId, $userId, $ownerId, $returningDate);
            $updateStatusInBooksTable = $this->requestModelObj->updateStatusInBooksTable($bookId, RequestController::STATUS_RETURNING);
            $this->notificationObj->sendNoti($ownerId, $userId, $bookId, $returningBookRequest);
            if ($updateReturningStatus && $updateStatusInBooksTable) {
                $jsonMessage = [
                    "isSuccess" => true,
                    "message" => "Book returning request sent successfully."
                ];
            } else {
                $jsonMessage = [
                    "isSuccess" => false,
                    "message" => "Something went wrong."
                ];
            }
        } else {
            $jsonMessage = [
                "isSuccess" => false,
                "message" => "Book not issued Or in returning process."
            ];
        }
        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);

    }
}
