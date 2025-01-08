<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/constants.php');
require_once(__DIR__ . '/logger.php');

class Database {
    private $connection;
    private $logger;

    // Constructor
    public function __construct() {
        $this->connect();
        $this->logger = new Logger();
    }

    // Connect to the database
    private function connect() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    // Close database connection
    public function closeConnection() {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    // Execute a database query
    public function query($sql) {
        $this->logger->debug($sql);
        return mysqli_query($this->connection, $sql);
    }

    // Get single row from database
    public function getSingleRow($sql) {
        $result = $this->query($sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

    // Define the getSingleValue method in your database class
    public function getSingleValue($sql) {
        $result = $this->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0]; // Return the first value in the result set
        } else {
            return null; // Return null if no value found
        }
    }


    // Get multiple rows from database
    public function getMultipleRows($sql) {
        $result = $this->query($sql);
        $rows = array();
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    // Insert data into database
    public function insert($table, $data, $returnInsertedId = false) {
        $columns = implode(', ', array_keys($data));
        $values = [];

        foreach ($data as $value) {
            if ($value === null) {
                $values[] = 'NULL';
            } else {
                $values[] = "'" . $value . "'";
            }
        }

        $values = implode(', ', $values);

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        $result = $this->query($sql);
        
        if($result && $returnInsertedId) return $this->getLastInsertedId();
        
        return $result;
    }

    // Update data in database
    public function update($table, $data, $condition) {
        $set = '';
        foreach ($data as $key => $value) {
            // Check if the value is null
            if ($value === null) {
                $set .= "$key = NULL, ";
            } else {
                // Escape and quote strings, leave numbers unquoted
                $escapedValue = is_numeric($value) ? $value : "'" . $this->escape($value) . "'";
                $set .= "$key = $escapedValue, ";
            }
        }
        $set = rtrim($set, ', ');
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->query($sql);
    }    

    // Update data in database if exists, otherwise insert
    public function updateOrInsert($table, $data, $condition, $returnInsertedId = false) {
        // Check if a record exists with the given condition
        $existingRecord = $this->getSingleRow("SELECT 1 FROM $table WHERE $condition");

        // If a record exists, update it
        if ($existingRecord) {
            return $this->update($table, $data, $condition);
        } else {
            // Otherwise, insert a new record
            return $this->insert($table, $data, $returnInsertedId);
        }
    }

    // Delete data from database
    public function delete($table, $condition) {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->query($sql);
    }

    public function escape($value) {
        // Assuming $this->connection is your database connection object
        return mysqli_real_escape_string($this->connection, $value);
    }
    
    // Get last inserted ID
    public function getLastInsertedId() {
        return mysqli_insert_id($this->connection);
    }

    // Check if username is already taken
    public function isUsernameTaken($username) {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $existing_user = $this->getSingleRow($sql);
        return ($existing_user !== null);
    }

    // Check if email is already taken
    public function isEmailTaken($email) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $existing_email = $this->getSingleRow($sql);
        return ($existing_email !== null);
    }

    // User registration
    public function registerUser($username, $password, $email, $full_name, $userType) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $data = array(
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email,
            'full_name' => $full_name,
            'userType' => $userType // New parameter for user type
        );
        return $this->insert('users', $data);
    }

    // User login
    public function loginUser($username, $password, $userType) {
        $sql = "SELECT * FROM users WHERE username = '$username' AND userType = '$userType'";
        $user = $this->getSingleRow($sql);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }
    
    public function loginUserStudentOrTeacher($username, $password) {
        $sql = "SELECT * FROM users WHERE username = '$username' AND (userType = 'STUDENT' OR userType = 'TEACHER')";
        $user = $this->getSingleRow($sql);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    public function loginUserAdminOrTeacher($username, $password) {
        $sql = "SELECT * FROM users WHERE username = '$username' AND (userType = 'ADMIN' OR userType = 'TEACHER')";
        $user = $this->getSingleRow($sql);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    // Insert contact information
    public function insertContactInfo($user_id, $name, $email, $phone_number, $message) {
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'message' => $message
        );
        return $this->insert('contact_information', $data);
    }

    // Get multiple rows from database with user information
    public function getContacts() {
        $sql = "SELECT c.*, u.username AS user_username, u.email AS user_email, u.full_name AS user_full_name
                FROM contact_information c
                LEFT JOIN users u ON c.user_id = u.id";
        return $this->getMultipleRows($sql);
    }

    // Get multiple rows from database with Categories
    public function getCategories() {
        $sql = "SELECT * FROM categories";
        return $this->getMultipleRows($sql);
    }

}

?>