<?php
/**
 * Home page the controls home.
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
 * Home Class controls home.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Home
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Constructor for home class.
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
     * Function getHome give home page.
     *
     * @return static twig file.
     */
    public function getHome()
    {
        $genreAndAuthorList = $this->bookM->getPresentGenreAndAuthorList();
        $bookRecentBook = $this->bookM->getRecentBook(0);
        $mostPopularBook = $this->bookM->getMostPopularBook(0);
        $mostUniqueGenre = $this->bookM->getMostUniqueGenre(0);
        $allBookList = $this->bookM->getAllBookList();
        return $this->_twig->render(
            'index.html.twig',
            ['bookList'=>$bookRecentBook,
             'genreAndAuthorList'=>
             $genreAndAuthorList,
             'mostPopularBook'=>$mostPopularBook,
             'mostUniqueGenre'=>$mostUniqueGenre,
              'allBookList'=>$allBookList]
        );
    }

    /**
     * Function getSignIn gives signin page.
     *
     * @return static twig file.
     */
    public function getSignIn()
    {
        return $this->_twig->render('signin.html.twig');
    }

    /**
     * Function getSignUp gives signUp page.
     *
     * @return static twig file.
     */
    public function getSignUp()
    {
        $lang = $this->bookM->getLanguage();
        $genre = $this->bookM->getGenre();
        return $this->_twig->render('signup.html.twig', ['lang'=>$lang, 'genre'=>$genre]);
    }

     /**
      * Function getHeader give header.
      *
      * @param $session user session.
      *
      * @return static twig file.
      */
    public function getHeader($session)
    {
        return $this->_twig->render('header.html.twig', ['session'=>$session]);
    }

     /**
      * Function getFooter give header.
      *
      * @return static twig file.
      */
    public function getFooter()
    {
        return $this->_twig->render('footer.html.twig');
    }

    /**
     * Function passwordRest reset password
     *
     * @return static twig file
     */
    public function passwordReset()
    {
        return $this->_twig->render('reset.html.twig');
    }

    /**
     * Function otpVerify for verify otp.
     *
     * @return static twig file.
     */
    public function otpVerify()
    {
        return $this->_twig->render('otpverify.html.twig');
    }

    /**
     * Function getLangAndGenre
     * 
     * @return void nothing 
     */
    public function getLangAndGenre()
    {
        $lang = $this->bookM->getLanguage();
        $genre = $this->bookM->getGenre();
        return $this->_twig->render('langandgenre.html.twig', ['lang'=>$lang, 'genre'=>$genre]);
    }

    /**
     * Function getSearchBookForm
     * 
     * @return void return search result
     */
    public function getSearchBookForm()
    {
        return $this->_twig->render('searchbook.html.twig');
    }
}
