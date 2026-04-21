<?php
 
namespace Transformer\Encoder;

use Transformer\Hasher\AbstractHasher;
use ValueObject\Transformer\HashType;
 
class HasherFactory
{
    #region FACTORY
    public static function create(HashType $type): AbstractHasher
    {
        return match ($type) {
        };
    }
    #endregion
}
 
