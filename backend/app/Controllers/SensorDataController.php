<?php

namespace App\Controllers;

require_once __DIR__ . "/../Entities/SensorData.php";

use App\Exceptions\SensorDataException;
use App\Services\SensorDataProcessor;
use App\Validators\SensorDataValidator;
use App\Entities\SensorData;
use App\Repositories\SensorDataTable;

class SensorDataController
{
    private SensorDataProcessor $processor;
    private SensorDataTable $repository;
    private SensorDataValidator $validator;

    public function __construct(
        SensorDataValidator $validator,
        SensorDataProcessor $processor,
        SensorDataTable $repository
    ) {
        $this->processor = $processor;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function msgHandle($msg)
    {
        $errors = $this->validator->sensorDataValidation($msg);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "[ERROR] $error" . PHP_EOL;
            }

            return $errors;
        }

        try {
            $sensorData = new SensorData(
                $msg["temp"],
                $msg["humid"]
            );

            $this->processor->processData(
                $sensorData->getTemp(),
                $sensorData->getHumid()
            );

            $this->repository->insertData(
                $sensorData->getTemp(), 
                $sensorData->getHumid()
            );
        } catch (\TypeError $e) {
            echo "[ERROR][TypeError] " . $e->getMessage() . PHP_EOL;
        } catch (SensorDataException $e) {
            echo "[ERROR] " . $e->getMessage() . PHP_EOL;
        }
    }
}