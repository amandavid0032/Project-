<?php
/**
 * DbConnection that controls database connectivity.
 *
 * PHP version 8.1.3
 *
 * @category Bookxchange
 * @package  Bookxchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
namespace Bookxchange\Bookxchange\Config;

/**
 * Db class handle Database method
 * 
 * @category Bookxchange
 * @package  Bookxchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class DbConnection
{
    private $_conn;
 
    /**
     * Constructor for the database connection.
     */
    public function __construct()
    {      
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bookexchange";
 
        $this->_conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    /**
     * Function to get connectionto database.
     * 
     * @return object return $conn database object.
     */
    public function getConnection(): object
    {
        return $this->_conn;
    }
}
?>
