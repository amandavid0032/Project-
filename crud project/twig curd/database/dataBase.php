<?php
namespace database;
use PDO;
use PDOException;
class main
{
    private $host = 'localhost';
    private $dbname = 'record';
    private $username = 'root';
    private $password = '';
    public $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
