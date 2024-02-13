<?php
class DB
{
    private $hostname = 'localhost';
    private $user = "root";
    private $pass = "";
    private $dbname = "clinic";
    private $conn = null;

    public function __construct()
    {
        $this->conn = mysqli_connect($this->hostname, $this->user, $this->pass, $this->dbname);
        return $this->conn;
    }
    public function filterPostData($data)
    {
        $data = htmlentities($data);
        $data = stripslashes($data);
        $data =  mysqli_real_escape_string($this->conn, $data);
        return $data;
    }

    public function inputData($query)
    {
        $result = mysqli_query($this->conn, $query);
        return $result ? mysqli_insert_id($this->conn) : false;
    }

    public function selectData($query)
    {
        $result = mysqli_query($this->conn, $query);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            $data = [];
            while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                array_push($data, $rows);
            }
            return $data;
        } else {
            return 0;
        }
    }
    function getCurrentTime()
    {
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        $get_time = $now->format('Y-m-d H:i:s');
        return $get_time;
    }
}

$db = new DB();
