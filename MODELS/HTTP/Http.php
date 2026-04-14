<?php

namespace Http;


require_once(__DIR__ . "/../PARAMETER/Parameter.php");

use Parameter\Parameter;

class Http
{
    #region FIELDS
    private array $parameters;
    #endregion

    #region CONSTRUCTOR
    public function __construct(array $parameters)
    {
        $this->setParameters($parameters);
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
        foreach ($parameters as $key => $header) {
            $this->addParameter($header, $key);
        }
    }

    public function addParameter(Parameter $parameter, mixed $key): void
    {
        $this->parameters[$key] = $parameter;
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