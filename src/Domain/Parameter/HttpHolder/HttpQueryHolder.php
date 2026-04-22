<?php

namespace Parameter\HttpHolder;

require_once(__DIR__ . "/HttpParameterHolder.php");

use InvalidArgumentException;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpParameterHolder;

class HttpQueryHolder extends HttpParameterHolder
{
    #region UTILS
    public function addParameter(Parameter $parameter): void
    {
        $key = $parameter->getKey();
        $value = $parameter->getValue();

        if (!is_string($key) || trim($key) === "") {
            throw new InvalidArgumentException(
                "Query parameter key must be non-empty string"
            );
        }

        if (!is_scalar($value) && $value !== null) {
            throw new InvalidArgumentException(
                "Query parameter value must be scalar or null"
            );
        }

        parent::addParameter($parameter);
    }
    #endregion
}