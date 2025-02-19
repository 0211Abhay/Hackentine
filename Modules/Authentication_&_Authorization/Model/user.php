<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $mobile_no;
    public $email;
    public $linkedin;
    public $github;
    public $password;
    public $role;
    public $university_id;
    public $created_at;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (first_name, last_name, mobile_no, email, linkedin, github, password, role, university_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bind_param("ssssssssi", 
            $this->first_name, $this->last_name, $this->mobile_no, 
            $this->email, $this->linkedin, $this->github, 
            $hashed_password, $this->role, $this->university_id
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get a user by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get all users
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update user information
    public function update($id) {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = ?, last_name = ?, mobile_no = ?, email = ?, linkedin = ?, github = ?, role = ?, university_id = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssii", 
            $this->first_name, $this->last_name, $this->mobile_no, 
            $this->email, $this->linkedin, $this->github, 
            $this->role, $this->university_id, $id
        );

        return $stmt->execute();
    }

    // Delete a user
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Authenticate user (Login)
    public function authenticate($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
