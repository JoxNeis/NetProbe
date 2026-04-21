<?php

namespace Parameter;

require_once(__DIR__ . "/../ValueObject/DataType.php");

use Exception;
use Encoder\EncoderFactory;
use ValueObject\DataType;
use ValueObject\Transformer\HashType;
use ValueObject\Transformer\EncodeType;
use ValueObject\Transformer\EncryptType;

class Parameter
{
    #region FIELDS
    private mixed $key;
    private mixed $value;
    private string $modified_value;
    private HashType $hash_type;
    private EncodeType $encode_type;
    private EncryptType $encrypt_type;
    private DataType $type;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        mixed $key,
        mixed $value,
        DataType $type
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setDataType($type);
    }
    #endregion

    #region GETTER
    public function getKey(): mixed
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
    public function setKey(mixed $key): void
    {
        if ($key === null) {
            throw new Exception("Parameter\'s key can't be null");
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

    #region TRANSFORMER

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