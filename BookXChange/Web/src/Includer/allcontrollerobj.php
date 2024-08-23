<?php
/**
 * All object of controller.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require '../../vendor/autoload.php';
use Bookxchange\Bookxchange\Config\Baseurl;
use Bookxchange\Bookxchange\Controller\User;
use Bookxchange\Bookxchange\Controller\Book;
use Bookxchange\Bookxchange\Controller\Home;
use Bookxchange\Bookxchange\Controller\Dashboard;
session_start();
$base = new Baseurl();
$baseurl = $base->getBaseurl(); 
$book = new Book($baseurl);
$user = new User($baseurl);
$home = new Home($baseurl);
$dashboard = new Dashboard($baseurl);
?>
