<?php

namespace Task;

use Exception;

class Task
{

    #region FIELDS
    private string $name;
    private string $slug;
    private string $address;
    private string $description;
    #endregion

    #region CONSTRUCTOR
    public function __construct($name = null, $address = null, $description = null)
    {
        if ($name !== null) {
            $this->setName($name);
            $this->makeSlug();
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
    public function getSlug(): string
    {
        return $this->slug;
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

    public function makeSlug()
    {
        $text = $this->name;
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/\s+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $this->slug = $text;
    }
    #endregion

    #region UTILS
    public function toArray()
    {
        $array = [];
        $array['name'] = $this->name;
        $array['slug'] = $this->slug;
        $array['address'] = $this->address;
        $array['description'] = $this->description;
        return $array;
    }

    public function fromArray(array $array)
    {

    }
    #endregion
}