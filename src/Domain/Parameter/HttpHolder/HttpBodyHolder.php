<?php

namespace Parameter\HttpHolder;

require_once(__DIR__ . "/HttpParameterHolder.php");
require_once(__DIR__ . "/../../ValueObject/EncodeType.php");

use InvalidArgumentException;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpParameterHolder;
use ValueObject\EncodeType;

class HttpBodyHolder extends HttpParameterHolder
{
    #region UTILS
    public function addParameter(Parameter $parameter): void
    {
        $key = $parameter->getKey();
        if (!is_string($key) || trim($key) === "") {
            throw new InvalidArgumentException(
                "Body parameter key must be non-empty string"
            );
        }
        parent::addParameter($parameter);
    }

    public function toBody()
    {
        $data = [];

        foreach (parent::getParameters() as $parameter) {

            $data[$parameter->getKey()] = $parameter;
        }

        return $data;
    }
    #endregion
}