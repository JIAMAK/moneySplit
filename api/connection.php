<?php
class Connection
{
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new \PDO("mysql:host=localhost;dbname=splitto", 'root', '12345678');
            // $this->conn = new \PDO("mysql:host=localhost;dbname=f33624h2_splito", 'root', 'Kfvfxjr29061988');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exc) {
            return array(
                'code' => 2,
                'error' => false,
                'data' => "Connection error: " . $exc->getMessage()
            );
        }

        return $this->conn;
    }
}
