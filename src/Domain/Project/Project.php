<?php

namespace Project;

require_once(__DIR__ . "/../Task/Task.php");

use Task\Task;
use DateTime;

class Project
{
    #region FIELDS

    private ?int $id = null;
    private string $name;
    private string $slug;
    private ?string $description = null;
    private DateTime $createdAt;
    private ?DateTime $lastAccessedAt = null;
    private ?string $address = null;
    private array $tasks = [];

    #endregion

    public function __construct(int $id, string $name, string $description, string $slug)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setDescription($description);
        $this->makeSlug();
        $this->createdAt = new DateTime();
    }

    #region GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }
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
    public function getAddress(): ?string
    {
        return $this->address;
    }
    #endregion

    #region SETTER
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

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


    public function setAddress(?string $address): self
    {
        $this->address = $address;
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

    public function toArray(){
        
    }
    #endregion
}