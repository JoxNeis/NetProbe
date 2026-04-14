<?php

namespace Parameter;

require_once(__DIR__ . "/../ENCODER/EncoderFactory.php");
require_once(__DIR__ . "/../../VALUES/PARAMETER/EncodeType.php");
require_once(__DIR__ . "/../../VALUES/PARAMETER/DataType.php");

use Exception;
use Encoder\EncoderFactory;
use Values\Parameter\DataType;
use Values\Parameter\EncodeType;

class Parameter
{
    #region FIELDS
    private string $key;
    private mixed $value;
    private DataType $type;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        string $key,
        mixed $value,
        DataType $type
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setDataType($type);
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

    public function getDataType(): DataType
    {
        return $this->type;
    }
    #endregion

    #region SETTER
    public function setKey(string $key): void
    {
        if ($key === "") {
            throw new Exception("Parameter\'s key can't be empty");
        }

        $this->key = $key;
    }

    public function setValue(mixed $value): void
    {
        if ($value === null) {
            throw new Exception("Parameter\'s value can't be null");
        }

        $this->value = $value;
    }

    public function setDataType(DataType $type): void
    {
        $this->type = $type;
    }

    #endregion

    #region ENCODE
    public function encode(EncodeType $encoding): void
    {
        if ($encoding === EncodeType::NONE) {
            return;
        }
        try {

            $encoder = EncoderFactory::create(
                $encoding
            );

            if ($this->type === DataType::FILE) {
                $this->checkFileIntegrity();
            }

            $this->value =
                $encoder->encode(
                    $this->value
                );

        } catch (Exception $e) {
            throw new Exception(
                "Encoding failed for '{$this->key}': "
                . $e->getMessage()
            );
        }
    }

    private function checkFileIntegrity(): void
    {
        if (!is_string($this->value)) {
            throw new Exception(
                "File address must be string path"
            );
        }

        if (!file_exists($this->value)) {
            throw new Exception(
                "File not found: {$this->value}"
            );
        }
    }
    #endregion

    #region UTILS
    public function toArray(): array
    {
        return [
            "key" => $this->getKey(),
            "value" => $this->getValue(),
            "type" => $this->getDataType()->value
        ];
    }
    #endregion
}