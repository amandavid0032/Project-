<?php

/**
 * SendMail.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

namespace App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class SendMail handles email sending.
 *
 * @category BookXchange
 * @package  BookXchange
 */
class SendMail
{
    private PHPMailer $_mail;

    /**
     * Constructor for SendMail.
     *
     * @param string $email Recipient email address.
     * @param string $token Reset token for password reset.
     * @throws Exception if mail sending fails.
     */
    public function __construct()
    {
        $this->_mail = new PHPMailer(true);

        try {
            // Server settings
            $this->_mail->isSMTP();
            $this->_mail->Host = 'smtp.gmail.com';
            $this->_mail->Port = 587;
            $this->_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->_mail->SMTPAuth = true;
            $this->_mail->Username = "amandavid9956@gmail.com";
            $this->_mail->Password = "athi gbhj zguq oroa";

            // Sender and recipient settings
            $this->_mail->setFrom('amandavid9956@gmail.com');

            // SMTP options
            $this->_mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Email content
            $this->_mail->isHTML(true);
            $this->_mail->Subject = 'Otp';
            $this->_mail->Body    = "Welcome to oxole its your otp";
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Mailer Error: {$this->_mail->ErrorInfo}");
        }
    }

    /**
     * Function getSendMailer 
     *
     * @return PHPMailer Return PHPMailer instance.
     */
    public function getSendMailer(): PHPMailer
    {
        return $this->_mail;
    }
}
