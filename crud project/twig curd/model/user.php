<?php
class dataBase
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

    // Login User Function
    public function loginUser($email, $password)
    {
        $sql = "SELECT * FROM user WHERE email=? AND password=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $password]);
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    // Select all user
    public function selectAllUser($offset = 0, $limit = null)
    {
        $sql = "SELECT * FROM user ORDER BY uid ASC LIMIT ?, ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result !== false) {
            return $result;
        } else {
            return false;
        }
    }



    // user Data
    public function userData($id = null)
    {
        $sql = "SELECT * FROM user WHERE uid=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $data : null;
    }

    // Search user
    public function searchUser($keyword, $order = 'ASC', $offset, $limit = null)
    {
        if ($limit !== null) {
            if (!empty($order)) {
                $searchSql = "SELECT * FROM user WHERE f_name LIKE :keyword ORDER BY $order LIMIT :offset, :limit";
            } else {
                $searchSql = "SELECT * FROM user WHERE f_name LIKE :keyword LIMIT :offset, :limit";
            }

            $stmt = $this->pdo->prepare($searchSql);
            $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result);

            return $result ? $result : false;
        } else {
            return false;
        }
    }


    // Count Total Records
    public function getTotalRecords($columns = null, $keyword = null)
    {
        if ($columns !== null && $keyword !== null) {
            $conditions = array();
            foreach ($columns as $column) {
                $conditions[] = "$column LIKE ?";
            }
            $whereClause = implode(" OR ", $conditions);
            $sql = "SELECT COUNT(*) AS total FROM user WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
            foreach ($columns as $index => $column) {
                $stmt->bindValue($index + 1, "%$keyword%", PDO::PARAM_STR);
            }
        } else {
            $sql = "SELECT COUNT(*) AS total FROM user";
            $stmt = $this->pdo->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // update Function 
    public function updateUser($values = array(), $condition)
    {
        if (empty($values)) {
            return false;
        }
        $setClause = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($values)));
        $sql = "UPDATE user SET $setClause WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $query = $stmt->execute();
        if ($query) {
            return $stmt->rowCount();
        } else {
            return false;
        }
    }

    // code for user sotring 
    public function getUsersSorted($order, $offset, $limit)
    {
        $order = strtolower($order);
        $sql = "SELECT * FROM `user` ORDER BY $order LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // Email check 
    public function getUserByEmail($email)
    {
        try {
            $query = "SELECT * FROM user WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $email));

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    // Regster User
    public function registerUser(array $values = array())
    {
        $columns = implode(', ', array_keys($values));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $sql = "INSERT INTO user ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $this->pdo->errorInfo()[2]);
        }
        try {
            $stmt->execute(array_values($values));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // delete user
    public function deleteUser($id)
    {
        try {
            $sql = "DELETE FROM user WHERE uid = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    //  email check
    public function emailCheck($keyword)
    {
        $searchSql = "SELECT * FROM user WHERE email LIKE ?";
        $stmt = $this->pdo->prepare($searchSql);
        $stmt->bindValue(1, "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : false;
    }


    // password reset
    public function resetPassword($email)
    {
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
        $sql = "UPDATE user SET reset_token_hash = ?, reset_token_expire_at = ? WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $token_hash, PDO::PARAM_STR);
        $stmt->bindValue(2, $expiry, PDO::PARAM_STR);
        $stmt->bindValue(3, $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $token; 
        } else {
            return false; 
        }
    }

}
