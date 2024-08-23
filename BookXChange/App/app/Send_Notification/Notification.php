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
                'data' => $notification
            );
        } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
        }
        $serverKey = $_ENV['FCM_SERVER_KEY'];
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            error_log('Curl failed: ' . curl_error($ch));
            return json_encode(['success' => 0, 'error' => curl_error($ch)]);
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
        $fcmToken = $this->getFcmToken($notificationReceiverId);
        if ($fcmToken === null) {
            return false;
        }

        $bookDetails = $this->getBookDetails($bookId);
        $userDetails = $this->getUserDetails($notificationSenderId);
        $requestDetails = $this->getRequestDetails($notificationSenderId, $notificationReceiverId, $bookId);
        $bookName = !empty($bookDetails) ? $bookDetails[0] : '';
        $bookImage = !empty($bookDetails) ? $bookDetails[1] : '';
        $requesterName = !empty($userDetails) ? $userDetails[0] : '';
        $requestId = !empty($requestDetails) ? $requestDetails[0] : '';

        switch ($notiType) {
            case 0:
                $messageBody = "You got a new book request";
                break;
            case 1:
                $messageBody = "Owner accepted your book request.";
                break;
            case 3:
                $messageBody = "Owner accepted your return book request.";
                break;
            case 4:
                $messageBody = "Owner rejected your book request.";
                break;
            default:
                $messageBody = "Notification message not specified for type: " . $notiType;
                break;
        }

        $notification = [
            "title" => "",

            "body" => $messageBody,
            "data" => [

                "bookName" => $bookName,
                "message" => "this is message  $messageBody",
                "requesterId" => $notificationSenderId,
                "bookImage" => $bookImage,
                "requesterName" => $requesterName,
                "bookId" => $bookId,
                "requestId" => $requestId,
                "type" => $notiType
            ]
        ];

        $deviceType = "Android";

        $notificationResult = $this->send_notification($fcmToken, $notification, $deviceType);

        // Save notification to database
        $this->saveNotification(
            $messageBody,
            $notificationSenderId,
            $notificationReceiverId,
            $bookId,
            $notiType
        );

        return true;
    }

    public function saveNotification(
        string $notificationTitle,
        int $notificationSenderId,
        int $notificationReceiverId,
        int $bookId,
        int $notiType
    ): bool {
        $saveNotificationStmt = $this->conn->prepare("INSERT into notification(noti_title, sender_id, receiver_id, book_id, notification_type) VALUES(?, ?, ?, ?, ?)");
        $saveNotificationStmt->bind_param("siiii", $notificationTitle, $notificationSenderId, $notificationReceiverId, $bookId, $notiType);
        $saveNotificationStmt->execute();
        if ($saveNotificationStmt->insert_id > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getBookDetails(int $bookId): array
    {
        $bookDetailStmt = $this->conn->prepare("select book_name, image from books where id = ?");
        $bookDetailStmt->bind_param("i", $bookId);
        $bookDetailStmt->execute();
        $result = $bookDetailStmt->get_result()->fetch_assoc();
        return [$result['book_name'], $result['image']];
    }

    public function getUserDetails(int $userId): array
    {
        $userDetailStmt = $this->conn->prepare("select user_name from register where id = ?");
        $userDetailStmt->bind_param("i", $userId);
        $userDetailStmt->execute();
        $result = $userDetailStmt->get_result()->fetch_assoc();
        return [$result['user_name']];
    }

    public function getRequestDetails(int $requesterId, int $ownerId, int $bookId): array
    {
        $requestStmt = $this->conn->prepare("SELECT id FROM request WHERE requester_id IN (?, ?) AND owner_id IN (?, ?) AND book_id = ?");
        $requestStmt->bind_param("iiiii", $requesterId, $ownerId, $requesterId, $ownerId, $bookId);
        $requestStmt->execute();
        $result = $requestStmt->get_result()->fetch_assoc();

        if ($result === null) {
            return [];
        }

        return [$result['id']];
    }


    public function getFcmToken(int $notificationReceiverFcmToken): ?string
    {
        $getFCMStmt = $this->conn->prepare("SELECT fcm_token FROM register WHERE id = ?");
        $getFCMStmt->bind_param("i", $notificationReceiverFcmToken);
        $getFCMStmt->execute();
        $result = $getFCMStmt->get_result()->fetch_assoc();

        if ($result === null) {
            return null;
        }

        return $result['fcm_token'];
    }
}
