<?php
class DB
{
    private $host = '';
    private $username = '';
    private $password = '';
    private $dbname = '';
    private $conn = false;
    private $error = [];

    function __construct($host, $username, $password, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

        if (!$this->conn) {
            die('Database not connected');
        }
        $this->cors();
    }

    public function insert(string $query = '')
    {
        $check = $this->invalidQuery($query);

        if ($check) {
            $this->responseHeader(200, false, ['message' => mysqli_insert_id($this->conn)]);
        } else {
            $this->responseHeader(0, true, ['message' => mysqli_error($this->conn)]);
        }
    }

    public function insertBatchQuery(string $string = '')
    {
        return mysqli_multi_query($this->conn, $string);
    }

    public function update(string $query = '')
    {
        if ($this->invalidQuery($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function select(string $query = '', bool $responseFromSelect = false)
    {
        $check = $this->invalidQuery($query);
        $numRows = mysqli_num_rows($check);
        $result = [];
        if ($numRows > 0) {
            while ($row = mysqli_fetch_assoc($check)) {
                array_push($result, $row);
            }

            if ($responseFromSelect == true) {
                $this->responseHeader(200, false, $result);
            }
        }
        return $result;
    }

    public function login(string $query = '', string $message = '')
    {

        $this->invalidQuery($query);
        $check = $this->select($query);

        if (!empty($check)) {
            $this->responseHeader(200, false, ['message' => $check]);
        } else {
            $this->responseHeader(401, true, ['message' => $message]);
        }
    }

    public function checkBeforeInsert(string $query = '', string $message = '')
    {
        $check = $this->invalidQuery($query);

        $numRows = mysqli_num_rows($check);

        if ($numRows > 0) {
            $this->responseHeader(200, true, ['message' => $message]);
        }
    }

    public function getJsonInput()
    {
        $output = file_get_contents('php://input');
        return json_decode($output, true);
    }

    public function errorResponse(int $code = 0, string $message = '')
    {
        $this->responseHeader($code, true, ['message' => $message]);
    }

    public function successResponse(int $code = 0, $message)
    {
        $this->responseHeader($code, false, ['message' => $message]);
    }

    private function responseHeader(int $code = 0, bool  $error = false, array $data = [])
    {
        header("Content-Type: application/json");
        echo json_encode(['status' => $code, 'error' => $error, 'data' => $data]);
        exit;
    }

    private function invalidQuery(string $query = '')
    {
        if ($query == '') {
            $this->responseHeader(0, true, ['message' => "Invalid Query"]);
        } else {
            try {
                $check = mysqli_query($this->conn, $query);
                if (!$check) {
                    throw new Exception(mysqli_error($this->conn));
                }
                return $check;
            } catch (Exception $e) {
                $this->responseHeader(401, true, ['message' => $e->getMessage()]);
            }
        }
    }

    private function cors()
    {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:

            // this one is the most important line
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


            // this one is the most important line    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }
}

$db = new DB('localhost', 'root', '', 'asset_management');
