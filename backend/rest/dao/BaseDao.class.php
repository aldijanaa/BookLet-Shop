<?php
require_once dirname(__FILE__) . "/../../config.php";



class BaseDao {
    protected $connection;
    private $table;

    // Making connection to db
    public function __construct($table) {
        $this->table = $table;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";charset=utf8;port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            print_r($e);
            throw $e;
        }
    }

    public function begin_transaction() {
        $this->connection->beginTransaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollback() {
        $this->connection->rollBack();
    }

    protected function parse_order($order) {
        $order_direction = substr($order, 0, 1) == '-' ? 'DESC' : 'ASC';
        $order_column = $order_direction == 'DESC' ? substr($order, 1) : $order;
        return [$order_column, $order_direction];
    }

    protected function execute_update($table, $id, $entity, $id_column = "id") {
        $query = "UPDATE {$table} SET ";
        foreach ($entity as $name => $value) {
            $query .= $name . "= :" . $name . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE {$id_column} = :id";

        $stmt = $this->connection->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
    }

    protected function query($query, $params) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params) {
        $results = $this->query($query, $params);
        return reset($results);
    }

    protected function execute($query, $params) {
        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }
        $stmt->execute();
        return $stmt;
    }

    public function insert($table, $entity) {
        try {
            $query = "INSERT INTO {$table} (";
            foreach ($entity as $column => $value) {
                $query .= $column . ", ";
            }
            $query = substr($query, 0, -2);
            $query .= ") VALUES (";
            foreach ($entity as $column => $value) {
                $query .= ":" . $column . ", ";
            }
            $query = substr($query, 0, -2);
            $query .= ")";

            $stmt = $this->connection->prepare($query);
            $stmt->execute($entity); // SQL injection prevention
            $entity['id'] = $this->connection->lastInsertId();
            return $entity;
        } catch (PDOException $e) {
            error_log("Insert SQL error: " . $e->getMessage() . " - Query: " . $query);
            error_log("Parameters: " . json_encode($entity)); // Log the parameters to check what is being passed
            throw new Exception("Insert SQL error: " . $e->getMessage());
        }
    }

    public function add($entity) {
        return $this->insert($this->table, $entity);
    }

    public function update($id, $entity) {
        $this->execute_update($this->table, $id, $entity);
    }

    public function get_by_id($id) {
        return $this->query_unique("SELECT * FROM " . $this->table . " WHERE id = :id", ["id" => $id]);
    }

    public function get_all($offset = 0, $limit = 25, $order = "id") {
        list($order_column, $order_direction) = self::parse_order($order);
        return $this->query("SELECT * FROM " . $this->table . " ORDER BY {$order_column} {$order_direction} LIMIT {$limit} OFFSET {$offset}", []);
    }
}
