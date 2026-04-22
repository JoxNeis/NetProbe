<?php

namespace Project;

require_once(__DIR__ . "/../Task/Task.php");

use Task\Task;
use DateTime;

class Project
{
    #region FIELDS
    private string $name;
    private string $slug;
    private ?string $description = null;
    private DateTime $createdAt;
    private ?DateTime $lastAccessedAt = null;
    private array $tasks = [];

    #endregion

    public function __construct(string $name, string $description,DateTime $createdAt = null)
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->makeSlug();
        if($createdAt) 
            $this->createdAt = $createdAt;
        else
            $this->createdAt = new DateTime();
    }

    #region GETTERS
    public function getName(): string
    {
        return $this->name;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function getLastAccessedAt(): ?DateTime
    {
        return $this->lastAccessedAt;
    }
    #endregion

    #region SETTER
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }


    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function setLastAccessedAt(?DateTime $lastAccessedAt): self
    {
        $this->lastAccessedAt = $lastAccessedAt;
        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function setTasks(array $tasks): self
    {
        foreach ($tasks as $task) {
            $this->addTask($task);
        }
        return $this;
    }

    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;
        return $this;
    }
    #endregion

    #region UTILS
    public function makeSlug()
    {
        $text = $this->name;
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/\s+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $this->slug = $text;
    }

    public function toArray(): array
    {
        $array = [];
        $array["name"] = $this->name;
        $array["slug"] = $this->slug;
        $array["description"] = $this->description;
        $array["created_at"] = $this->createdAt;
        $array["description"] = $this->description;
        $array["tasks"] = [];
        foreach ($this->tasks as $task) {
            $array["tasks"][] = $task->toArray();
        }
        return $array;
    }

    public static function fromArray(array $array):self
    {
        return new self($array['name'],$array['description']);
    }
    #endregion
}