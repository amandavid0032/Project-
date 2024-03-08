<?php
class Database
{
    public $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "record") or die("Connection failed:" . mysqli_connect_error());
    }

    //Insert Function
    public function insert($table, array $values = array())
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $columns = implode(', ', array_keys($values));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...array_values($values));
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    //Select Function
    public function select($table, $rows = '*', $limit = null, $page = 1, $where = null)
    {
        $sql = "SELECT $rows FROM $table";
        // $sql .= " WHERE uid != $loggedInUserId";
        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        if ($limit !== null) {
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT $offset, $limit";
        }
        $query = $this->conn->query($sql);
        
        if ($query) {
            return $query;
        } else {
            return false;
        }
    }
    //update Function 
    public function update($table, $values = array(), $where = null)
    {
        if (empty($values)) {
            return false;
        }
        $setValues = array();
        foreach ($values as $key => $value) {
            $setValues[] = "$key='" . $this->conn->real_escape_string($value) . "'";
        }
        $setClause = implode(', ', $setValues);
        $sql = "UPDATE $table SET $setClause";
        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        $query = $this->conn->query($sql);
        if ($query) {
            return $this->conn->affected_rows;
        } else {
            return false;
        }
    }

    // Delete function
    public function deleteRecord($table, $id)
    {
        $sql = "DELETE FROM $table WHERE uid = '$id'";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    // Search
    public function search($table, $columns, $keyword, $limit = null)
    {
        if ($limit !== null) {
            $conditions = array();
            foreach ($columns as $column) {
                $conditions[] = "$column LIKE '%$keyword%'";
            }
            $searchSql = "SELECT * FROM $table WHERE " . implode(" OR ", $conditions) . " LIMIT $limit";
            $query = $this->conn->query($searchSql);
            if ($query) {
                return $query;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // Count Function
    public function count($table, $columns = null, $keyword = null)
    {
        if ($columns !== null && $keyword !== null) {
            $conditions = array();
            foreach ($columns as $column) {
                $conditions[] = "$column LIKE '%$keyword%'";
            }
            $whereClause = implode(" OR ", $conditions);
            $sql = "SELECT COUNT(*) AS total FROM $table WHERE $whereClause";
        } else {
            $sql = "SELECT COUNT(*) AS total FROM $table";
        }

        $query = $this->conn->query($sql);
        $result = $query->fetch_assoc();
        return $result['total'];
    }

  public  function loginSession()
    {
        session_start();
        if (!isset($_SESSION['email'])) {
            header("location: ./../index.php");
            exit;
        }
    }

   public function noLoginSession()
    {
        session_start();
        if (isset($_SESSION['email'])) {
            if ($_SESSION['type'] == 1) {
                header("location: user-list.php");
            } elseif ($_SESSION['type'] == 0) {
                header("location: index.php");
            }
        }
    }
    //Close mysqli    
    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
