<?php

namespace Parameter;

require_once(__DIR__ . "/../ENCODER/EncoderFactory.php");
require_once(__DIR__ . "/../../VALUES/EncodeType.php");
require_once(__DIR__ . "/../../VALUES/ParameterDataType.php");

use Exception;
use Encoder\EncoderFactory;
use EncodeType;
use ParameterDataType;

class Parameter
{
    #region FIELDS
    private string $key;
    private mixed $value;
    private ParameterDataType $type;
    private EncodeType $encoding = EncodeType::NONE;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        string $key,
        mixed $value,
        ParameterDataType $type,
        EncodeType $encoding
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setParameterDataType($type);
        $this->setEncoding($encoding);
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

    public function getEncoding(): EncodeType
    {
        return $this->encoding;
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

    public function setParameterDataType(ParameterDataType $type): void
    {
        $this->type = $type;
    }

    public function setEncoding(EncodeType $encoding): void
    {

        $this->encoding = $encoding;
    }
    #endregion

    #region ENCODE
    public function encode(): void
    {
        if ($this->encoding === EncodeType::NONE) {
            return;
        }

        try {

            $encoder = EncoderFactory::create(
                $this->encoding
            );

            if ($encoder === null) {
                throw new Exception(
                    "Unsupported encoding: "
                    . $this->encoding->value
                );
            }

            if ($this->type === ParameterDataType::FILE) {
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
            "encoding" => $this->getEncoding()->value,
        ];
    }
    #endregion
}