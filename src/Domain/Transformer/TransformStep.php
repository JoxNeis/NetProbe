<?php

namespace Transformer;

use ValueObject\Transformer\EncodeType;
use ValueObject\Transformer\HashType;
use ValueObject\Transformer\EncryptType;
use InvalidArgumentException;
class TransformStep
{
    #region FIELD
    private EncodeType|HashType|EncryptType $type;
    private ?string $encryptionKey;
    #endregion

    #region CONSTRUCT
    public function __construct(
        EncodeType|HashType|EncryptType $type,
        ?string $encryptionKey = null
    ) {
        if ($type instanceof EncryptType && $encryptionKey === null) {
            throw new InvalidArgumentException(
                "Encryption step requires an encryption key."
            );
        }
        $this->type = $type;
        $this->encryptionKey = $encryptionKey;
    }
    #endregion

    #region GETTER
    public function getType(): EncodeType|HashType|EncryptType
    {
        return $this->type;
    }

    public function getEncryptionKey(): ?string
    {
        return $this->encryptionKey;
    }
    #endregion

}