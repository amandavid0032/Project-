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
class Baseurl
{
    /**
     * Function getBaseurl give baseurl
     *
     * @return string return url string
     */
    public function getBaseurl():string
    {
        $baseurl = 'http://localhost/bookXchange/web/';
        return $baseurl;
    }
}
?>
