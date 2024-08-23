<?php
/**
 * Bookcontroller that controls all the book functionality
 *
 * PHP version 7.4.30
 *
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
namespace Book\Bookxchange\Controller;


require_once __DIR__ .'/../../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
/**
 * SettingController, a controller for the setting, tha handles all the 
 * setting related functions
 * 
 * @category   CategoryName
 * @package    Bookxchange
 * @author     Original Author <ajeettharu0@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
class SettingController
{


    private $_loader;
    private $_twig;
    private $_setting_m;
    /**
     * Constructor for the setting controller.
     * 
     * @param $setting_m is the object for the setting model,
     *                   that call all the functions in setting model page.
     */
    public function __construct($setting_m)
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../view/templates');
        $this->twig = new Environment($this->loader);
        $this->setting_m = $setting_m;
    }

    /**
     * Function to get all the setting, and get them into the twig file.
     * 
     * @return returns all the settings in a 
     * file and send them to setting.html.twig file.
     */
    public function allSettings()
    {

        $allSetting = $this->setting_m->allSettingsModel();
        // echo "<pre>";
        // print_r($allSetting);
        return $this->twig->render(
            'setting.html.twig', ["all_setting" => $allSetting]
        );
        
        
    }
    /**
     * Fucntion to apply the changes that done in the setting page
     * 
     * @param $title   is the string value, that contains the title of the page.
     * @param $mail    is the string value, that contains the mail 
     *                 address of the page.
     * @param $welcome is the welcome message from the setting page.
     * 
     * @return Nothing to return.
     */
    public function applyChange(string $title,string $mail,string $welcome)
    {

            
        $setTitle = $this->setting_m->setTitleModel($title);
        if ($setTitle) {
                header('location:setting.php');
        }

        $setMail = $this->setting_m->setMailModel($mail);
        if ($setMail) {
            header('location:setting.php');
        }

        $setWlc = $this->setting_m->setWlcModel($welcome);
        if ($setWlc) {
            header('location:setting.php');
        }

    }

    /**
     * Function to upload the image for the logo.
     * 
     * @param $logo is the image for the logo.
     * 
     * @return nothing to return
     */
    public function updateLogo($logo)
    {
        // global $setting_m;
        $img_name = $logo['name'];
        $img_path = $logo['tmp_name'];

        $dest = "../img/".$img_name;
        move_uploaded_file($img_path, $dest);
        $logoRst = $this->setting_m->updateLogoModel($dest);
        if ($logoRst) {
            $_SESSION['success'] = "Successfully applied your Logo";
            header('location:setting.php');
        }
    }

    /**
     * Function to get updated title to show in the title section of the page.
     * 
     * @return return the title of the page 
     */
    public function getTitle() : string
    {

        $title = $this->setting_m->getTitleModel();
        return $title;
    }


    /**
     * Function to get the updated welcome message in the login section
     * 
     * @return returns the welcome message.
     */
    public function getWelcome() : string
    {

        $welcome = $this->setting_m->getWelcomeModel();
        return $welcome;
    }

    /**
     * Function to get the logo from the database
     * 
     * @return the logo image from the database
     */
    public function getLogo() : string
    {
        $logo = $this->setting_m->getLogoModel();
        $logo_name = $logo['value'];
        $logo_name = substr($logo_name, 7);
        return $logo_name;

    }
    /**
     * Function to display the language
     * 
     * @return the logo image from the database
     */
    public function language() : string
    {
        $allLanguages = $this->setting_m->langModel();
        return $this->twig->render(
            'language.html.twig', ["languages" => $allLanguages]
        );
    }

    public function addLang(string $language)
    {
        $addLangRst = $this->setting_m->addLangModel($language);
        // $allLanguages = $this->setting_m->langModel();
        // return $this->twig->render(
        //     'language.html.twig', ["languages" => $allLanguages]
        // );
        header('location:language.php?added=true');
    }

    /**
     * Function to display the language
     * 
     * @return the logo image from the database
     */
    public function genre() : string
    {
        $allGenre = $this->setting_m->genreModel();
        return $this->twig->render(
            'genre.html.twig', ["genres" => $allGenre]
        );
    }

    public function addGenre(string $genre)
    {
        $addGenreRst = $this->setting_m->addgenreModel($genre);
        // $allLanguages = $this->setting_m->langModel();
        // return $this->twig->render(
        //     'language.html.twig', ["languages" => $allLanguages]
        // );
        header('location:genre.php?added=true');
    }

}
