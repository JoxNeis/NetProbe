<?php

namespace Parameter\HttpHolder;


require_once(__DIR__ . "/../Parameter.php");

use Parameter\Parameter;

class HttpParameterHolder
{
    #region FIELDS
    protected array $parameters = [];
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
    }
    #endregion

    #region GETTER
    public function getParameters(): array
    {
        return $this->parameters;
    }
    #endregion

    #region SETTER    
    public function setParameters(array $parameters): void
    {
        foreach ($parameters as $param) {
            $this->addParameter($param);
        }
    }

    public function addParameter(Parameter $parameter): void
    {
        $this->parameters[$parameter->getKey()] = $parameter;
    }
    #endregion

    #region UTILS
    public function toArray()
    {
        $array = [];
        foreach ($this->parameters as $key => $parameter) {
            $array[$key] = $parameter->toArray();
        }
        return $array;
    }
    #endregion
}