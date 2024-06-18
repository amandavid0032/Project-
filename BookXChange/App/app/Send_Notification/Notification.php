<?php
namespace App\Send_Notification;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class Notification
{
    protected $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function send_notification($registatoin_ids, $notification, $device_type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if ($device_type == "Android") {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification,
            );
        $fields1 = json_encode($fields);
        } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
        }

        $headers = array('Authorization:key=AAAAiQBVY5I:APA91bF5sXtVEaxChuxplYcLNTnCnhVTCCcdkjo8wQbFFWVLi8ZsBgq4vSrkjkvnJX7mNF8dSuKmLHT0Kyx8vY2iJfSAsCjrDikQOBb4IPorI23L02qBBQqVcF7aRu_71uS92GRdZGZr', 'Content-Type:application/json');
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function sendNoti(
        int $notificationReceiverId,
        int $notificationSenderId,
        int $bookId,
        int $notiType
    ) {
        $registatoin_ids = $this->getFcmToken($notificationReceiverId);
        $bookDetails = $this->getBookDetails($bookId);
        $userDetails = $this->getUserDetails($notificationSenderId);
        $requestDetails = $this->getRequestDetails($notificationSenderId, $notificationReceiverId, $bookId);
        if ($notiType == 0) {
            $messageBody = "You got a new book request";
        } elseif ($notiType == 5) {
            $messageBody = "Borrower cancelled request";
        } elseif ($notiType == 1) {
            $messageBody = "Owner accepted your book request.";
        } elseif ($notiType == 3) {
            $messageBody = "Owner accepted your return book request.";
        } elseif ($notiType == 4) {
            $messageBody = "Owner rejected your book request.";
        } elseif ($notiType == 7) {
            $messageBody = "Book reviewed by borrower.";
        } elseif ($notiType == 6) {
            $messageBody = "Book reviewed by owner.";
        } elseif ($notiType == 2) {
            $messageBody = "Borrower wants to return your book.";
        }
        $notification = [
            "title" => "Oxole",
            "body" => $messageBody,
            "data" => [
                "bookName" => $bookDetails[0],
                "message" => " this is message",
                "requesterId" => $notificationSenderId,
                "bookImage" => $bookDetails[1],
                "requesterName" => $userDetails[0],
                "bookId" => $bookId,
                "requestId" => $requestDetails[0],
                "type" => $notiType
            ]
        ];
        $device_type = "Android";
        $notif_send_result = $this->send_notification($registatoin_ids, $notification, $device_type);
        $this->saveNotification(
            $messageBody,
            $notificationSenderId,
            $notificationReceiverId,
            $bookId,
            $notiType
        );
    }

    public function saveNotification(
        string $notificationTitle,
        int $notificationSenderId,
        int $notificationReceiverId,
        int $bookId,
        int $notiType
    ) : bool {
        $saveNotificationStmt = $this->conn->prepare("INSERT into notification(noti_title, sender_id, receiver_id, book_id, notification_type) VALUES(?, ?, ?, ?, ?)");
        $saveNotificationStmt->bind_param("siiii", $notificationTitle, $notificationSenderId, $notificationReceiverId, $bookId, $notiType);
        $saveNotificationStmt->execute();
        if ($saveNotificationStmt->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getBookDetails(int $bookId) : array
    {
        $bookDetailStmt = $this->conn->prepare("select book_name, image from books where id = ?");
        $bookDetailStmt->bind_param("i", $bookId);
        $bookDetailStmt->execute();
        $result = $bookDetailStmt->get_result()->fetch_assoc();
        return [$result['book_name'], $result['image']];
    }

    public function getUserDetails(int $userId) : array
    {
        $userDetailStmt = $this->conn->prepare("select user_name from register where id = ?");
        $userDetailStmt->bind_param("i", $userId);
        $userDetailStmt->execute();
        $result = $userDetailStmt->get_result()->fetch_assoc();
        return [$result['user_name']];
    }

    public function getRequestDetails(int $requesterId, int $ownerId, int $bookId) : array
    {
        $requestStmt = $this->conn->prepare("select id from request where requester_id in (?, ?) and owner_id in (?, ?) and book_id = ?");
        $requestStmt->bind_param("iiiii", $requesterId, $ownerId, $requesterId, $ownerId, $bookId);
        $requestStmt->execute();
        $result = $requestStmt->get_result()->fetch_assoc();
        return [$result['id']];
    }

    public function getFcmToken(int $notificationReceiverFcmToken) : string
    {
        $getFCMStmt = $this->conn->prepare("select fcm_token from register where id = ?");
        $getFCMStmt->bind_param("i", $notificationReceiverFcmToken);
        $getFCMStmt->execute();
        $fcmToken = $getFCMStmt->get_result()->fetch_assoc();
        return $fcmToken['fcm_token'];
    }
}