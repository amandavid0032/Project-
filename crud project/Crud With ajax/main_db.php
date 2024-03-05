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
        $table_Columns = implode(",", array_keys($values));
        $table_Values = implode("','", $values);
        $sql = "INSERT INTO $table ($table_Columns) VALUES ('$table_Values')";
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }
    
    //Select Function
    public function select($table, $rows = '*', $limit = null, $where = null)
    {
        $sql = "SELECT $rows FROM $table";
        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        if ($limit !== null) {
            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $page = (int)$_GET['page'];
            } else {
                $page = 1;
            }
            $start = ($page - 1) * $limit;
            $sql .= " LIMIT $start,$limit";
        }
        $query = $this->conn->query($sql);
        if ($query) {
            return $query;
        } else {
            return false;
        }
    }
    // Pagination  function
    public function pagination($table, $limit = null, $where = null)
    {
        if ($limit != null) {
            $sql = "SELECT COUNT(*) FROM $table";
            if ($where != null) {
                $sql .= " WHERE $where";
            }
            $query = $this->conn->query($sql);
            $total_record = (int)$query->fetch_array()[0];
            $total_page = ceil((int)$total_record / (int)$limit);
            $url = $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET);
            $output = "<ul class='pagination'>";
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            if ($currentPage > 1) {
                $output .= "<li class='page-item'><a class='page-link' href='$url&page=" . ($currentPage - 1) . "'>Previous</a></li>";
            }
            for ($i = 1; $i <= $total_page; $i++) {
                $class_name = ($i == $currentPage) ? "active" : "";
                $output .= "<li class='page-item $class_name'><a class='page-link' href='$url&page=$i'>{$i}</a></li>";
            }
            if ($currentPage < $total_page) {
                $output .= "<li class='page-item'><a class='page-link' href='$url&page=" . ($currentPage + 1) . "'>Next</a></li>";
            }
            $output .= "</ul>";
            return $output;
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
        $bindTypes = '';
        $bindValues = array();
    
        foreach ($values as $key => $value) {
            $setValues[] = "$key=?";
            $bindTypes .= 's';    
            $bindValues[] = $value;
        }
        $setClause = implode(', ', $setValues);
        $sql = "UPDATE $table SET $setClause";
        if ($where !== null) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        if (!empty($bindValues)) {
            $stmt->bind_param($bindTypes, ...$bindValues);
        }
        if ($stmt->execute()) {
            return $stmt->affected_rows;
        } else {
            return false;
        }
    }
    
    // Delete function
    public function deleteRecord($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = '$id'";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
// Search function with pagination 
// public function search($table, $columns, $keyword, $currentPage = 1, $limit = null)
// {
//     if ($limit != null) {
//         $countSql = "SELECT COUNT(*) FROM $table WHERE ";
//         $conditions = array();
//         foreach ($columns as $column) {
//             $conditions[] = "$column LIKE '%$keyword%'";
//         }
//         $countSql .= implode(" OR ", $conditions);
//         $query = $this->conn->query($countSql);
//         $totalRecords = $query->fetch_array()[0];
//         $total_page = ceil($totalRecords / $limit);
//         $offset = ($currentPage - 1) * $limit;
//         $searchSql = "SELECT * FROM $table WHERE ";
//         $searchSql .= implode(" OR ", $conditions);
//         $searchSql .= " LIMIT $offset, $limit";

//         $query = $this->conn->query($searchSql);
//         if ($query) {
//             $paginationLinks = $this->pagination($table, $limit, implode(" OR ", $conditions));
//             return array('results' => $query, 'pagination' => $paginationLinks);
//         } else {
//             return false;
//         }
//     } else {
//         return false; 
//     }
// }    
    //Close mysqli    
    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
