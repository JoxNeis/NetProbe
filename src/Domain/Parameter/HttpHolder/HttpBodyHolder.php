<?php

namespace Parameter\HttpHolder;

require_once(__DIR__ . "/HttpParameterHolder.php");

use InvalidArgumentException;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpParameterHolder;

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

    #endregion
}