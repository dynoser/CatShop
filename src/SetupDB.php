<?php
namespace dynoser\catshop;

class SetupDB {
    public $conn;
    
    public $db_name;
    public $table_cat;
    public $table_prod;
    
    public function __construct($conf) {
        $this->conn = $conf->conn;
        $this->db_name = $conf->db_name;
        $this->table_cat = $conf->table_cat;
        $this->table_prod = $conf->table_prod;
    }
    
    public function init() {
        // Create Database (if not exists)
        $this->createDataBase();
        
        // Create categories table (if not exists)
        $this->createTableCategories();

        // Create products table (if not exists)
        $this->createTableProducts($this->table_prod);

        // Add test data to both tables
        $this->addTestCategories();
        $this->addTestProducts();
    }

    public function createDataBase() {
        $db_name = $this->db_name;

        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
        if ($this->conn->query($sql) === TRUE) {
            echo "Database '$db_name' created successfully\n";
        } else {
            die ("Error creating database: " . $this->conn->error . "\n");
        }

        $this->conn->select_db($this->db_name);
    }
    
    public function createTableCategories() {
        
        $table = $this->table_cat;


        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            description VARCHAR(255),
            date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table '$table' created successfully\n";
        } else {
            die ("Error creating table '$table': " . $this->conn->error . "\n");
        }
    }
    
    public function addTestCategories() {
        $table = $this->table_cat;

        $sql = "INSERT INTO `$table` (name, description) VALUES
          ('Категория 1', 'Описание категории 1'),
          ('Категория 2', 'Описание категории 2'),
          ('Категория 3', 'Описание категории 3')";
        if ($this->conn->query($sql) === TRUE) {
            echo "Test data added to table 'categories' successfully\n";
        } else {
            die("Error adding test data to table 'categories': " . $this->conn->error . "\n");
        }
    }

    public function createTableProducts() {

        $table = $this->table_prod;

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            description VARCHAR(255),
            price DECIMAL(10, 2) NOT NULL,
            image VARCHAR(255) NOT NULL,
            category_id INT(6) UNSIGNED,
            date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES {$this->table_cat}(id)
        )";
        if ($this->conn->query($sql) === TRUE) {
            echo "Table 'products' created successfully\n";
        } else {
            die("Error creating table 'products': " . $this->conn->error . "\n");
        }
    }

    public function addTestProducts() {
        $table = $this->table_prod;
        
        $current_date = date("Y-m-d H:i:s");
        $min_days_ago = 1;
        $max_days_ago = 60;
        $rndtm = "DATE_SUB('$current_date', INTERVAL FLOOR(RAND() * ($max_days_ago - $min_days_ago + 1) + $min_days_ago) DAY)";

        $sql = "INSERT INTO $table (name, description, price, image, category_id, date_added) VALUES
        ('Товар 1', 'Описание товара 1', 10.50, 'images/product1.jpg', 1, $rndtm),
        ('Товар 2', 'Описание товара 2', 15.00, 'images/product2.jpg', 2, $rndtm),
        ('Товар 3', 'Описание товара 3', 5.99, 'images/product3.jpg', 1, $rndtm),
        ('Товар 4', 'Описание товара 4', 20.75, 'images/product4.jpg', 3, $rndtm),
        ('Товар 5', 'Описание товара 5', 7.50, 'images/product5.jpg', 2, $rndtm),
        ('Товар 6', 'Описание товара 6', 18.99, 'images/product6.jpg', 1, $rndtm),
        ('Товар 7', 'Описание товара 7', 12.50, 'images/product7.jpg', 2, $rndtm),
        ('Товар 8', 'Описание товара 8', 9.99, 'images/product8.jpg', 3, $rndtm),
        ('Товар 9', 'Описание товара 9', 14.25, 'images/product9.jpg', 1, $rndtm),
        ('Товар 10', 'Какой-то товар 10', 154.25, 'images/product10.jpg', 3, $rndtm)";

        if ($this->conn->query($sql) === TRUE) {
            echo "Test data added to table 'products' successfully\n";
        } else {
            die("Error adding test data to table 'products': " . $this->conn->error . "\n");
        }
    }
}