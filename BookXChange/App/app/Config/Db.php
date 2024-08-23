<?php

namespace App\Config;

class Db
{
    private $conn;

    public function __construct()
    {
        // $servername = "127.0.0.1";
        // $username = "Userbookexchange";
        // $password = "r2#bE7gH1J";
        // $dbname = "bookexchange";


        // $servername = "182.76.151.197";
        // $username = "amn"; 
        // $password = "N0p@sword";
        // $dbname = "bookexchange";

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bookexchange";


        $this->conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
