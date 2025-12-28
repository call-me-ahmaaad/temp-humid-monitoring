<?php

namespace App\Controllers;

require_once __DIR__ . "/../Validators/MQTTValidator.php";
require_once __DIR__ . "/../Entities/SensorData.php";
require_once __DIR__ . "/../Services/SensorDataServices.php";
require_once __DIR__ . "/../Exceptions/SensorDataException.php";

use App\Exceptions\SensorDataException;
use App\Services\SensorDataServices;
use App\Validators\MQTTValidator;
use App\Entities\SensorData;

class MQTTController
{
    public function msgHandle($topic, $payload)
    {
        $msg = json_decode($payload, true);

        $validator = new MQTTValidator();
        $errors = $validator->sensorDataValidation($msg);

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

            $service = new SensorDataServices();
            $service->printSensorData(
                $sensorData->getTemp(),
                $sensorData->getHumid()
            );
        } catch (\TypeError $e) {
            echo "[ERROR][TypeError] " . $e->getMessage() . PHP_EOL;
        } catch (SensorDataException $e) {
            echo "[ERROR][{$e->getCode()}] " . $e->getMessage() . PHP_EOL;
        }
    }
}