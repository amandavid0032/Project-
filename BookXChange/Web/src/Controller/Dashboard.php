<?php
/**
 * Index page that controls login.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

namespace Bookxchange\Bookxchange\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Model\BookM;

/**
 * Dashboard class that controls dashboard.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Dashboard
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Construtor for dashboard class.
     *
     * @param $baseurl is baseurl.
     */
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $this->bookM = new BookM();
    }

    /**
     * Function getDashboard give the dashboard.
     *
     * @param $session is session of user.
     * 
     * @return static twig file.
     */
    public function getDashboard(int $session)
    {
        
        $genreAndAuthorList = $this->bookM->getPresentGenreAndAuthorList();
        $genreAndAuthorList = $this->bookM->getPresentGenreAndAuthorList();
        $bookRecentBook = $this->bookM->getRecentBook($session);
        $mostPopularBook = $this->bookM->getMostPopularBook($session);
        $mostUniqueGenre = $this->bookM->getMostUniqueGenre($session);
        $allBookList = $this->bookM->getAllBookList();
        return $this->_twig->render(
            'dashboard.html.twig',
            ['bookList'=>$bookRecentBook,
            'genreAndAuthorList'=>$genreAndAuthorList,
            'mostPopularBook'=>$mostPopularBook,
            'mostUniqueGenre'=>$mostUniqueGenre,
            'allBookList'=>$allBookList]
        );
    }
    
}
