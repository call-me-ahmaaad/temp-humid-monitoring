<?php

namespace App\Infrastructure\Database;

use PDO;
use PDOException;
use App\Services\Logger\SensorDataLogger;

class DBConnection
{
    private array $dbConfig;
    private SensorDataLogger $logger;
    public function __construct(SensorDataLogger $logger)
    {
        $this->dbConfig = require __DIR__ . "/../../config/database.php";
        $this->logger = $logger;
    }

    public function connect(): PDO|null
    {
        $host = $this->dbConfig["host"];
        $username = $this->dbConfig["username"];
        $password = $this->dbConfig["password"];
        $dbName = $this->dbConfig["database"];
        $dbPort = $this->dbConfig["port"];

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbName;port=$dbPort", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->logger->writeLog("Successfully connected to database: $dbName");
            echo "[SUCCESS] Successfully connected to database" . PHP_EOL;

            return $conn;
        } catch (PDOException $e) {
            $this->logger->writeLog("Failed to connect database $dbName: {$e->getMessage()}", "ERROR");
            echo "[ERROR] Failed to connect database $dbName: {$e->getMessage()}" . PHP_EOL;

            return null;
        }
    }
}