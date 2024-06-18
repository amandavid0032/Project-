<?php
/**
 * RequestController that controls all the request functionality
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
namespace Book\Bookxchange\Controller;


require_once __DIR__ .'/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


/**
 * RequesterController class that all the functions related to Request
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
class RequestController
{
    private $_loader;
    private $_twig;


    /**
     * Contructor for the RequestController.
     * 
     * @param $reqst_m is the object for the RequestController
     */
    public function __construct($reqst_m)
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../view/templates');
        $this->twig = new Environment($this->loader);
        $this->reqst_m = $reqst_m;
    }

    /**
     * Function to show all the request in the request list page
     * 
     * @return returns a to the twig, that contain all the book requests,
     * book returned and book borrowed.
     */
    public function getReqsts()
    {
        $requestList = $this->reqst_m->getRequests();
        if ($requestList) {
            return $this->twig->render(
                'rqst_list.html.twig', ['requestList' => $requestList]
            );

        }
    }



}