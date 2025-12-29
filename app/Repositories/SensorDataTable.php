<?php

namespace App\Repositories;

use App\Services\Logger\SensorDataLogger;
use PDO;
use PDOException;

class SensorDataTable
{
    private ?PDO $pdo;
    private SensorDataLogger $logger;
    public function __construct(PDO $pdo, SensorDataLogger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }
    public function insertData(float $temp, float $humid)
    {
        if ($this->pdo === null) {
            $this->logger->writeLog("DB connection not available. Skip insert", "WARNING");
            return;
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO sensor_data (temp, humid, created_at) VALUES (:temp, :humid, :created_at)"
            );

            $stmt->execute([
                ":temp" => $temp,
                ":humid" => $humid,
                ":created_at" => $this->dateNow()
            ]);

            $this->logger->writeLog("Sensor data inserted: [\"temp\": $temp, \"humid\": $humid]", "SUCCESS");
        } catch (PDOException $e) {
            $this->logger->writeLog("Insert failed: {$e->getMessage()}", "ERROR");
        }
    }

    private function dateNow()
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Jakarta')))
            ->format('Y-m-d H:i:s');
    }
}