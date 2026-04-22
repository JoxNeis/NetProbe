<?php

namespace Task;

use Exception;

class Task
{

    #region FIELDS
    private int $id;
    private string $name;
    private string $slug;
    private string $address;
    private string $description;
    #endregion

    #region CONSTRUCTOR
    public function __construct($id = null,$name = null, $address = null, $description = null)
    {
        if ($id !== null) {
            $this->setId($id);
        }
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
    public function getId():int{
        return $this->id;
    }
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
    public function setId(int $id){
        if($id<=0){
            throw new Exception("Task\'s Id can\'t be lower than zero");
        }
        $this->id = $id;
    }
    
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