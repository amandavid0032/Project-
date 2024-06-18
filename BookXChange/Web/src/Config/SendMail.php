<?php
/**
 * Baseurl.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
namespace Bookxchange\Bookxchange\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Baseurl give baseurl.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class SendMail
{
    private $_mail;
     /**
     * Constructor for SendMail.
     *
     */
    public function __construct()
    {
        $this->_mail = new PHPMailer(true);
        $this->_mail->SMTPDebug = 2;                                       
        $this->_mail->isSMTP();                                            
        $this->_mail->Host       = 'smtp.gmail.com';                    
        $this->_mail->SMTPAuth   = true;                             
        $this->_mail->Username   = 'chaudharymilan996@gmail.com';                 
        $this->_mail->Password   = 'togxnlufaaussulf';                        
        $this->_mail->SMTPSecure = 'ssl';                              
        $this->_mail->Port       = 465;  
        $this->_mail->isHTML(true); 
        $this->_mail->setFrom('chaudharymilan996@gmail.com');  
    }
    /**
     * Function getSendMailer 
     *
     * @return object return object
     */
    public function getSendMailer():object
    {
        return $this->_mail;
    }
}
?>
