<?php
namespace dynoser\catshop;

class Config {
    
    public $conn;
    
    //Database name:
    public $db_name = "simple_store";

    // Categories table name
    public $table_cat = "categories";

    // Products table name
    public $table_prod = "products";

    public function __construct(bool $db_init = false) {
        // connect to database: server, user, password [,dbname]
        $this->conn = new \mysqli(
            "localhost", // DB Server name
            "root",      // DB User
            "Test123",   // DB Password
            $db_init ? '' : $this->db_name);

        // check error
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}