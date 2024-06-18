<?php
/**
 * RequestModel that controls all the queries functionality
 *
 * PHP version 7.4.30
 *
 * @category  CategoryName
 * @package   Bookxchange
 * @author    Original Author <ajeettharu0@gmail.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/PackageName
 * @see       NetOther, Net_Sample::Net_Sample()
 * @since     File available since Release 1.2.0
 */
namespace Book\Bookxchange\Model;


/**
 * RequesteModel class that all the functions related to Request
 *
 * PHP version 8.1.3
 *
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
class RequestModel
{
    /**
     * Contructor for the RequestModel.
     * 
     * @param $conn is the object for the connection with the database
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Function to query all the request from the database
     * 
     * @return returns to the twig, that contain all the book requests,
     * book returned and book borrowed.
     */
    public function getRequests() : array
    {
        $allRequestStmt = $this->conn->prepare(
            "SELECT borrower.user_name as Requester, r.requester_id,
            b.book_name as Book, r.book_id, owner.user_name as Book_Owner,
            r.owner_id, r.status, r.rqst_date, r.issued_date,
            r.return_date
            FROM request as r 
            INNER JOIN register as borrower ON r.requester_id = borrower.id
            INNER JOIN books as b ON b.id = r.book_id
            INNER JOIN register as owner ON owner.id = r.owner_id"
        );
        $allRequestStmt->execute();
        $result = $allRequestStmt->get_result();
        $allRequestData = $result->fetch_all(MYSQLI_ASSOC);

        return $allRequestData;

    }

}