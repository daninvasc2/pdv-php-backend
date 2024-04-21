<?php

require_once 'Utils/Utils.php';

/**
 * Singleton class to handle database connection
 */
class DatabaseConfig {
    private static $instance;
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    /**
     * It reads the environment variables and sets the database connection
     */
    private function __construct() {
        $this->conn = null;
        setEnvVars(__DIR__ . '/../.env');

        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');
    }

    /**
     * It returns the instance of the class
     * 
     * @return DatabaseConfig
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DatabaseConfig();
        }

        return self::$instance;
    }

    /**
     * It returns the connection object
     * 
     * @return PDO
     */
    public function getConnection(): PDO {
        if (!$this->conn) {
            try {
                $this->conn = new PDO('pgsql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Erro de conexão: ' . $e->getMessage();
                die();
            }
        }

        return $this->conn;
    }

    /**
     * It closes the connection
     */
    public function closeConnection() {
        $this->conn = null;
    }

    /**
     * It closes the connection when the object is destroyed
     */
    public function __destruct() {
        $this->closeConnection();
    }
}
