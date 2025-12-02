<?php

//Database connection
class Database
{
    // private properties store database connection info
    private $host = DB_HOST;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $pdo;

    public function getConnection()
    {
        //creates connection if one doesnt exist
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                //sets pdo to throw exceptions
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //return row as array
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}

?>
