<?php

namespace Project;

use DateTime;

class Project
{
    #region FIELDS
    
    // Recommended additions
    private ?int $id = null;
    private string $status = 'pending'; // e.g., 'pending', 'active', 'completed', 'archived'
    private ?DateTime $dueDate = null;

    // Improved existing fields
    private string $name;
    private string $slug;
    private ?string $description = null;
    private DateTime $createdAt;
    private ?DateTime $lastAccessedAt = null;
    private ?string $address = null;
    private array $tasks = [];

    #endregion

    /**
     * Constructor to ensure required fields are populated upon creation.
     */
    public function __construct(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->createdAt = new DateTime(); // Automatically set creation time
    }

    #region GETTERS & SETTERS

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLastAccessedAt(): ?DateTime
    {
        return $this->lastAccessedAt;
    }

    public function setLastAccessedAt(?DateTime $lastAccessedAt): self
    {
        $this->lastAccessedAt = $lastAccessedAt;
        return $this;
    }

    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTime $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
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
        $this->tasks = $tasks;
        return $this;
    }

    /**
     * Helper method to easily add a single task.
     */
    public function addTask(string $task): self
    {
        $this->tasks[] = $task;
        return $this;
    }

    #endregion
}