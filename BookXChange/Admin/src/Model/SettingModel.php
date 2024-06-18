<?php
/**
 * Bookmodal that queries all the queries related to the setting.
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
namespace Book\Bookxchange\Model;

/**
 * SettingModel, a controller for the setting, tha handles all the 
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
class SettingModel
{
    private $_conn;

    /**
     * Constructor for the setting controller.
     * 
     * @param $conn is the object for the database connection.
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Function to get all the setting from the setting table.
     * 
     * @return retuns the array with all setting model.
     */
    public function allSettingsModel() : array
    {

        $allSettingStmt = $this->conn->prepare("select * from setting");
        $allSettingStmt->execute();
        $all = $allSettingStmt->get_result();

        $allSetting = $all->fetch_all(MYSQLI_ASSOC);
        return $allSetting;

    }

    /**
     * Function to set the title for the page.
     * 
     * @param $title is a string value, obtained from the setting page.
     * 
     * @return string boolean value after setting the title.
     */
    public function setTitleModel(String $title) : bool
    {

        $site_title = "site_title";
        $updateTitleStmt = $this->conn->prepare("update setting set value = ? where name = ?");
        $updateTitleStmt->bind_param("ss", $title, $site_title);
        $updateTitleStmt->execute();
        return true;
    }

    /**
     * Function to set mail
     * 
     * @param $mail is the unique string value.
     * 
     * @return returns true after setting the mail in
     * mail section of the setting table. 
     */
    public function setMailModel(string $mail) : bool
    {
        $mail_from = "mail_from";

        $updateMailStmt = $this->conn->prepare("update setting set value = ? where name = ?");
        $updateMailStmt->bind_param("ss", $mail, $mail_from);
        $updateMailStmt->execute();
        return true;
    }

    /**
     * Function to set welcome message
     * 
     * @param $welcome is the unique string value.
     * 
     * @return returns boolean value true after setting the welcome . 
     */
    public function setWlcModel(string $welcome) : bool
    {
        $welcome_text = "welcome_text";

        $updateWelcomeStmt = $this->conn->prepare(
            "update setting set value = ? where name = ?"
        );
        $updateWelcomeStmt->bind_param("ss", $welcome, $welcome_text);
        $updateWelcomeStmt->execute();
        return true;
    }

    /**
     * Function to upload the logo image.
     * 
     * @param $dest is the string.
     * 
     * @return returns boolean value true after setting the welcome . 
     */
    public function updateLogoModel($dest) : bool
    {
        $logo = "logo";
        $updateLogoStmt = $this->conn->prepare(
            "update setting set value = ? where name = ?"
        );
        $updateLogoStmt->bind_param("ss", $dest, $logo);
        $updateLogoStmt->execute();
        return true;

    }


    /**
     * Function to get the title and show the message in the title section.
     * 
     * @return the title from the database.
     */
    public function getTitleModel() : string
    {

        $title = "site_title";
        $getTitleStmt = $this->conn->prepare(
            "select value from setting where name = ?"
        );
        $getTitleStmt->bind_param("s", $title);
        $getTitleStmt->execute();
        $getTitleResult = $getTitleStmt->get_result();
        $site_title = $getTitleResult->fetch_array(MYSQLI_ASSOC);
        return $site_title['value'];

    }

    /**
     * Function to get the image name fromt he database
     * 
     * @return returns the logo image fetched from the database.
     */
    public function getLogoModel() : array
    {

        $logo = "logo";
        $getLogoStmt = $this->conn->prepare(
            "select value from setting where name = ?"
        );
        $getLogoStmt->bind_param("s", $logo);
        $getLogoStmt->execute();
        $getLogoRst = $getLogoStmt->get_result();
        $logo_name = $getLogoRst->fetch_array(MYSQLI_ASSOC);
        return $logo_name;

    }


    /**
     * Function to get the all the languages
     * 
     * @return returns the list of languages.
     */
    public function langModel()
    {
        $getLangStmt = $this->conn->prepare("select * from language order by name");
        $getLangStmt->execute();
        $getLangRst = $getLangStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $getLangRst;
    }

    public function addLangModel(string $language)
    {
        $addLangStmt = $this->conn->prepare("insert into language(name) values(?)");
        $addLangStmt->bind_param("s", $language);
        $addLangStmt->execute();
        return true;
    }

    /**
     * Function to get the all the genres
     * 
     * @return returns the list of genre.
     */
    public function genreModel()
    {
        $getGenreStmt = $this->conn->prepare("select * from genre order by genre");
        $getGenreStmt->execute();
        $getGenreRst = $getGenreStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $getGenreRst;
    }

    public function addgenreModel(string $genre)
    {
        $addGenreStmt = $this->conn->prepare("insert into genre(genre) values(?)");
        $addGenreStmt->bind_param("s", $genre);
        $addGenreStmt->execute();
        return true;
    }

}
