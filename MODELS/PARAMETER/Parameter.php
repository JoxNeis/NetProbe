<?php

namespace Parameter;

require_once(__DIR__ . "/../../VALUES/ParameterDataType.php");

use Exception;
use ParameterDataType;

class Parameter
{
    #region FIELDS
    private string $key;
    private mixed $value;
    private ParameterDataType $type;
    private bool $encoded = false;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        string $key,
        mixed $value,
        ParameterDataType $type
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setParameterDataType($type);
    }
    #endregion

    #region GETTER
    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getParameterDataType(): ParameterDataType
    {
        return $this->type;
    }

    public function is_encoded(): bool
    {
        return $this->encoded;
    }
    #endregion

    #region SETTER
    public function setKey(string $key): void
    {
        if ($key === "") {
            throw new Exception("Parameter key can't be empty");
        }

        $this->key = $key;
    }

    public function setValue(mixed $value): void
    {
        if ($value === null) {
            throw new Exception("Parameter value can't be null");
        }

        $this->value = $value;
    }

    public function setParameterDataType(
        ParameterDataType $type
    ): void {
        $this->type = $type;
    }
    #endregion

    #region UTILS
    public function encodeValue(): void
    {
        if ($this->type !== ParameterDataType::FILE) {
            throw new Exception("Parameter's type is not a FILE.");
        }

        if (!file_exists($this->value)) {
            throw new Exception("Parameter's does not exist");
        }

        $file = file_get_contents($this->value);

        if ($file === false) {
            throw new Exception("Failed to read file");
        }
        $this->value = base64_encode($file);
        $this->encoded = true;
    }

    public function toArray(): array{
        return [
            "key" =>$this->getKey(),
            "value" =>$this->getValue(),
            "encoded" =>$this->is_encoded(),
        ];
    }
    #endregion
}