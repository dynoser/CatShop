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
        
        // Drop tables (if exists)
        $this->dropTableProducts();
        $this->dropTableCategories();

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
    
    public function dropTableProducts() {
        $table = $this->table_prod;
        
        $truncate_sql = "DROP TABLE IF EXISTS $table;";
        if ($this->conn->query($truncate_sql) === TRUE) {
            echo "Table '$table' dropped\n";
        } else {
            die("Error drop table '$table': " . $this->conn->error . "\n");            
        }
    }

    public function dropTableCategories() {
        $table = $this->table_cat;

        $truncate_sql = "DROP TABLE IF EXISTS $table;";
        if ($this->conn->query($truncate_sql) === TRUE) {
            echo "Table '$table' dropped\n";
        } else {
            die("Error drop table '$table': " . $this->conn->error . "\n");
        }        
    }
    
    public function addTestCategories() {
        $table = $this->table_cat;

        $sql = "INSERT INTO `$table` (name, description) VALUES
          ('appliances', 'Крупная и мелкая бытовая техника для дома'),
          ('car & motorbike', 'Автомобильные и мотоциклетные запчасти и аксессуары'),
          ('tv, audio & cameras', 'Телевизоры, аудио- и видеотехника, фотоаппараты'),
          ('sports & fitness', 'Спортивные товары и товары для фитнеса'),
          ('grocery & gourmet foods', 'Продукты питания, в том числе изысканные гурманские деликатесы'),
          ('home & kitchen', 'Товары для дома и кухни'),
          ('pet supplies', 'Товары для домашних животных'),
          ('stores', 'Торговые сети и магазины'),
          ('toys & baby products', 'Игрушки и товары для младенцев и детей'),
          ('kids\' fashion', 'Одежда и аксессуары для детей'),
          ('bags & luggage', 'Сумки и чемоданы для путешествий и повседневного использования'),
          ('accessories', 'Аксессуары для женщин и мужчин'),
          ('women\'s shoes', 'Женская обувь'),
          ('beauty & health', 'Косметика и товары для здоровья'),
          ('men\'s shoes', 'Мужская обувь'),
          ('women\'s clothing', 'Женская одежда'),
          ('industrial supplies', 'Промышленное оборудование и материалы'),
          ('men\'s clothing', 'Мужская одежда'),
          ('music', 'Музыкальные инструменты и записи'),
          ('home, kitchen, pets', 'Товары для дома, кухни и домашних животных')";

        if ($this->conn->query($sql) === TRUE) {
            echo "Test data added to table '$table' successfully\n";
        } else {
            die("Error adding test data to table '$table': " . $this->conn->error . "\n");
        }
    }

    public function createTableProducts()
    {
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
            die("Error creating table '$table': " . $this->conn->error . "\n");
        }
    }
    
    public function addTestProducts() {
        $table = $this->table_prod;
        $filename = '../data/Products.csv';

        if (($handle = fopen($filename, "r")) !== FALSE) {
            
            
            $sql = "INSERT INTO $table (category_id, name, description, image, price, date_added) VALUES";
            $first_line = true;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($first_line) {
                    $first_line = false;
                    continue;
                }

                $category_id = $data[0];
                $name = $this->conn->real_escape_string($data[1]);
                $description = $this->conn->real_escape_string($data[2]);
                $image = $this->conn->real_escape_string($data[3]);
                $price = $data[4];
                $current_date = date("Y-m-d H:i:s");
                $min_days_ago = 1;
                $max_days_ago = 60;
                $rndtm = "DATE_SUB('$current_date', INTERVAL FLOOR(RAND() * ($max_days_ago - $min_days_ago + 1) + $min_days_ago) DAY)";

                $sql .= " ('$category_id', '$name', '$description', '$image', '$price', $rndtm),";
            }

            fclose($handle);

            $sql = rtrim($sql, ",") . ";";

            if ($this->conn->query($sql) === TRUE) {
                echo "Test data added to table 'products' successfully\n";
            } else {
                die("Error adding test data to table 'products': " . $this->conn->error . "\n");
            }
        } else {
            die("Error opening file $filename\n");
        }
    }

}