<?php

namespace Transformer;

use ValueObject\Transformer\EncodeType;
use ValueObject\Transformer\HashType;
use ValueObject\Transformer\EncryptType;

class TransformStep
{
    private EncodeType|HashType|EncryptType $type;
    private ?string $encryptionKey;

    public function __construct(
        EncodeType|HashType|EncryptType $type,
        ?string $encryptionKey = null
    ) {
        if ($type instanceof EncryptType && $encryptionKey === null) {
            throw new \InvalidArgumentException(
                "Encryption step requires an encryption key."
            );
        }
        $this->type = $type;
        $this->encryptionKey = $encryptionKey;
    }

    public function getType(): EncodeType|HashType|EncryptType
    {
        return $this->type;
    }

    public function getEncryptionKey(): ?string
    {
        return $this->encryptionKey;
    }
}