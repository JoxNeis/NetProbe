<?php

namespace Parameter\HttpHolder;

require_once(__DIR__ . "/HttpParameterHolder.php");
require_once(__DIR__ . "/../../ValueObject/HttpHeaderCategory.php");

use InvalidArgumentException;
use ValueObject\HttpHeaderCategory;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpParameterHolder;

class HttpHeaderHolder extends HttpParameterHolder
{
    #region UTILS

    public function addParameter(Parameter $parameter): void
    {
        if (!($parameter->getKey() instanceof HttpHeaderCategory)) {
            throw new InvalidArgumentException(
                "Http Header Parameter key must be instance of HttpHeaderCategory"
            );
        }

        if ($parameter->getValue() === "") {
            throw new InvalidArgumentException(
                "Http Header Parameter value can't be empty"
            );
        }

        parent::addParameter($parameter);
    }

    public function toHeader(): array
    {
        $header = [];

        foreach (parent::getParameters() as $parameter) {
            $header[] =
                $parameter->getKey()->value .
                ": " .
                $parameter->getValue();
        }

        return $header;
    }
    #endregion
}