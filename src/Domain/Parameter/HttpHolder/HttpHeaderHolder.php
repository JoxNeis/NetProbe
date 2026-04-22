<?php

namespace Parameter\HttpHolder;

require_once(__DIR__ . "/HttpParameterHolder.php");
require_once(__DIR__ . "/../../ValueObject/HttpHeaderCategory.php");

use InvalidArgumentException;
use ValueObject\HttpHeaderCategory;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpParameterHolder;
use ValueObject\Http\HttpBodyType;

class HttpHeaderHolder extends HttpParameterHolder
{
    #region UTILS
    public function setBodyType(Parameter $parameter)
    {
        if (!($parameter->getKey() == HttpHeaderCategory::CONTENT_TYPE)) {
            throw new InvalidArgumentException(
                "Http Header Parameter key must be HttpHeaderCategory::CONTENT_TYPE"
            );
        }

        if (!($parameter->getValue() instanceof HttpBodyType)) {
            throw new InvalidArgumentException(
                "Http Header Parameter value must be instance of HttpBodyType"
            );
        }

        parent::addParameter($parameter);
    }
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

        if ($parameter->getKey() == HttpHeaderCategory::CONTENT_TYPE || $parameter->getValue() instanceof HttpBodyType) {
            throw new InvalidArgumentException(
                "Use setBodyType() function for setting Http Content-Type header"
            );
        }
        parent::addParameter($parameter);
    }
    #endregion
}