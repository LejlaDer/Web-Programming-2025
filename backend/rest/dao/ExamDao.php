<?php

class ExamDao {

    private $table_name;
    protected $connection;

    /**
     * constructor of dao class
     */
    public function __construct($table_name){

      $this->table_name = $table_name;
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */

           $this->connection = new PDO(
            "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
            Config::DB_USER(),
            Config::DB_PASSWORD(),
            [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

          /** TODO
           * Create new connection
           */
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get customer information
     */
    public function get_customers(){
      $stmt = $this->connection->prepare("
      SELECT id, first_name, last_name FROM customers");
      $stmt->execute();
      return $stmt->fetchAll();

    }

    /** TODO
     * Implement DAO method used to get customer meals
     */
    public function get_customer_meals($customer_id) {
    $stmt = $this->connection->prepare("
        SELECT f.name AS food_name,
        f.brand AS food_brand,
        m.created_at AS meal_date
        FROM meals m
        JOIN foods f ON m.food_id = f.id
        WHERE m.customer_id = :customer_id");
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    return $stmt->fetchAll();
    }

    /** TODO
     * Implement DAO method used to save customer data
     */
    public function add($entity)
    {
        $query = "INSERT INTO " . $this->table_name . " (";
        foreach ($entity as $column => $value) {
            $query .= $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":" . $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        $entity['id'] = $this->connection->lastInsertId();
        return $entity;
    }

    /** TODO
     * Implement DAO method used to get foods report
     */
    public function get_foods_report(){
      $page = $_GET['page'] ?? 1;
      $page_size = $_GET['page_size'] ?? 10;
      $offset = ($page - 1) * $page_size;

      $stmt = $this->connection->prepare("
         SELECT 
            f.id,
            f.name,
            f.brand,
            f.image_url AS image,
            SUM(CASE WHEN fn.nutrient_id = 1 THEN fn.quantity ELSE 0 END) AS energy,
            SUM(CASE WHEN fn.nutrient_id = 2 THEN fn.quantity ELSE 0 END) AS protein,
            SUM(CASE WHEN fn.nutrient_id = 3 THEN fn.quantity ELSE 0 END) AS fat,
            SUM(CASE WHEN fn.nutrient_id = 4 THEN fn.quantity ELSE 0 END) AS fiber,
            SUM(CASE WHEN fn.nutrient_id = 5 THEN fn.quantity ELSE 0 END) AS carbs
          FROM foods f
          LEFT JOIN food_nutrients fn ON f.id = fn.food_id
          GROUP BY f.id
          LIMIT :limit OFFSET :offset
      ");
      $stmt->bindValue(':limit', (int)$page_size, PDO::PARAM_INT);
      $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll();
}
}
?>
