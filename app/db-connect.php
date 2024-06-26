<?php
class Database {
    private $host = 'db'; // Utilisez 'db' car c'est le nom du service dans docker-compose.yaml
    private $db_name = 'absence';
    private $username = 'root';
    private $password = 'devops';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Essayez de vous connecter à la base de données MySQL
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
