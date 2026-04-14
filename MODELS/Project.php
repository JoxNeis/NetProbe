<?php

namespace Model;

require_once("Task.php");
require_once("../Module/config.php");

use DateTime;
use Exception;
use Model\Task;

class Project
{
    #region FIELDS
    private string $name;
    private string $root_address;
    private string $description;
    private DateTime $created_at;
    private array $tasks;
    #endregion

    #region CONSTRUCTOR
    public function __construct($name = null, $root_address = null, $description = null, $created_at = null, $tasks = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
        if ($root_address !== null) {
            $this->setRootAddress($root_address);
        }
        if ($description === null) {
            $this->setDescription($description);
        }
        if ($created_at !== null) {
            $this->setCreatedAt($created_at);
        } else {
            $this->created_at = new DateTime();
        }
        if ($tasks !== null) {
            $this->setTasks($tasks);
        }
    }
    #endregion

    #region GETTER
    public function getName(): string
    {
        return $this->name;
    }

    public function getRootAddress(): string
    {
        return $this->root_address;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
    #endregion

    #region SETTER
    public function setName(string $name)
    {
        if ($name === "")
            throw new Exception("Project\'s name can\'t be empty");
        $this->name = $name;
    }
    public function setRootAddress(string $root_address)
    {
        if ($root_address === "")
            throw new Exception("Project\'s address can\'t be empty");
        $this->root_address = $root_address;
    }
    public function setDescription(string $description)
    {
        if ($description === "")
            throw new Exception("Project\'s description can\'t be empty");
        $this->description = $description;
    }

    public function setCreatedAt(string $created_at)
    {
        if ($created_at === "")
            throw new Exception("Project\'s creation date can\'t be empty");
        $this->created_at = DateTime::createFromFormat(DEFAULT_DATETIME_FORMAT, $created_at);
        if (!$this->created_at) {
            throw new Exception("Project\'s invalid created_at format. Expected Y-m-d H:i:s");
        }
    }

    public function addTask(Task $task)
    {
        if (!($task instanceof Task))
            throw new Exception("Project\'s task must be an instance of task.");
        $this->tasks[] = $task;
    }

    public function setTasks(array $tasks)
    {
        foreach ($tasks as $key => $task) {
            $this->addTask($task);
        }
    }
    #endregion

    #region UTILS
    public function toArray()
    {
        $array = [];
        $array["name"] = $this->name;
        $array["root_address"] = $this->root_address;
        $array["description"] = $this->description;
        $array["created_at"] = $this->created_at;
        $tasks = [];
        foreach ($this->tasks as $key => $task) {
            $tasks[] = $task->toArray();
        }
        $array['tasks'] = $tasks;
        return $array;
    }

    public function fromArray(array $array)
    {
        $requiredKeys = ['name', 'root_address', 'description', 'created_at', 'tasks'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $array)) {
                throw new Exception("Project\'s missing required key: {$key}");
            }
        }

        if (!is_string($array['name']))
            throw new Exception("Project\'s name must be string");

        if (!is_string($array['root_address']))
            throw new Exception("Project\'s root_address must be string");

        if (!is_string($array['description']))
            throw new Exception("Project\'s description must be string");

        if (!is_string($array['created_at']))
            throw new Exception("Project\'s created_at must be string");

        if (!is_array($array['tasks']))
            throw new Exception("Project\'s tasks must be array");

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $array['created_at']);
        if (!$date) {
            throw new Exception("Invalid created_at format. Expected Y-m-d H:i:s");
        }

        $this->setName($array['name']);
        $this->setRootAddress($array['root_address']);
        $this->setDescription($array['description']);
        $this->setCreatedAt($array['created_at']);

        $task = new Task();
        foreach ($array['tasks'] as $taskData) {
            if (!is_array($taskData)) {
                throw new Exception("Each task must be an array");
            }
            $task->fromArray($taskData);
            $this->addTask($task);
        }
    }
    #endregion
}

