<?php

namespace Parameter;

require_once(__DIR__ . "/../ValueObject/DataType.php");
require_once(__DIR__ . "/../Transformer/Encoder/EncoderFactory.php");
require_once(__DIR__ . "/../Transformer/Hasher/HasherFactory.php");
require_once(__DIR__ . "/../Transformer/Encryptor/EncryptorFactory.php");
require_once(__DIR__ . "/TransformStep.php");

use Exception;
use ValueObject\DataType;
use Transformer\Encoder\EncoderFactory;
use Transformer\Hasher\HasherFactory;
use Transformer\Encryptor\EncryptorFactory;
use Transformer\TransformStep;
use ValueObject\Transformer\HashType;
use ValueObject\Transformer\EncodeType;
use ValueObject\Transformer\EncryptType;

class Parameter
{
    #region FIELDS
    private mixed $key;
    private mixed $value;
    private DataType $type;
    private array $steps;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        mixed $key,
        mixed $value,
        DataType $type,
        array $steps = []
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setDataType($type);
        $this->setSteps($steps);
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

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getModifiedValue(): string
    {
        $result = (string) $this->value;

        foreach ($this->steps as $step) {
            $type = $step->getType();

            $result = match (true) {
                $type instanceof EncodeType => EncoderFactory::create($type)->encode($result),
                $type instanceof HashType => HasherFactory::create($type)->hash($result),
                $type instanceof EncryptType => EncryptorFactory::create($type)->encrypt($result, $step->getEncryptionKey()),
            };
        }

        return $result;
    }
    #endregion

    #region SETTER
    public function setKey(mixed $key): void
    {
        if ($key === null) {
            throw new Exception("Parameter's key can't be null");
        }
        $this->key = $key;
    }

    public function setValue(mixed $value): void
    {
        if ($value === null) {
            throw new Exception("Parameter's value can't be null");
        }
        $this->value = $value;
    }

    public function setDataType(DataType $type): void
    {
        $this->type = $type;
    }

    public function setSteps(array $steps): void
    {
        foreach ($steps as $step) {
            $this->addStep($step);
        }
    }

    public function addStep(TransformStep $step)
    {
        if (!($step instanceof TransformStep)) {
            throw new \InvalidArgumentException(
                "Each step must be an instance of TransformStep."
            );
        }
        $this->steps[] = $step;
    }
    #endregion

    #region UTILS
    public function toArray(): array
    {
        $array = [
            "key" => $this->getKey(),
            "value" => $this->getValue(),
            "type" => $this->getDataType()->value
        ];
        $array["steps"] = [];
        foreach ($this->steps as $step) {
            $array["steps"][] = $step->toArray();
        }
        return $array;
    }
    #endregion
}