<?php

namespace Request;

use Exception;

class Request
{

    #region FIELDS
    private string $name;
    private string $address;
    private string $description;
    #endregion

    #region CONSTRUCTOR
    public function __construct($name = null, $address = null, $description = null, $payload = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
        if ($address !== null) {
            $this->setAddress($address);
        }
        if ($description !== null) {
            $this->setDescription($description);
        }
    }
    #endregion

    #region GETTER
    public function getName(): string
    {
        return $this->name;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getDescription()
    {
        return $this->description;
    }
    #endregion

    #region SETTER
    public function setName(string $name)
    {
        if ($name === "") {
            throw new Exception("Task\'s name can\'t be empty");
        }
        $this->name = $name;
    }

    public function setAddress(string $address)
    {
        if ($address === "") {
            throw new Exception("Task\'s address can\'t be empty");
        }
        $this->address = $address;
    }
    public function setDescription(string $description)
    {
        if ($description === "") {
            throw new Exception("Task\'s description can\'t be empty");
        }
        $this->description = $description;

    }
    #endregion

    #region UTILS
    public function toArray()
    {
        $array = [];
        $array['name'] = $this->name;
        $array['address'] = $this->address;
        $array['description'] = $this->description;
        return $array;
    }

    public function fromArray(array $array)
    {

    }
    #endregion
}