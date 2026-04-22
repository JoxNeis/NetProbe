<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/Sha256Hasher.php");
require_once(__DIR__ . "/Sha384Hasher.php");
require_once(__DIR__ . "/Sha512Hasher.php");
require_once(__DIR__ . "/Sha3_224Hasher.php");
require_once(__DIR__ . "/Sha3_256Hasher.php");
require_once(__DIR__ . "/Sha3_384Hasher.php");
require_once(__DIR__ . "/Sha3_512Hasher.php");
require_once(__DIR__ . "/Blake2bHasher.php");
require_once(__DIR__ . "/Blake2sHasher.php");
require_once(__DIR__ . "/Md5Hasher.php");
require_once(__DIR__ . "/Sha1Hasher.php");
require_once(__DIR__ . "/Crc32Hasher.php");
require_once(__DIR__ . "/Crc32bHasher.php");
require_once(__DIR__ . "/WhirlpoolHasher.php");
require_once(__DIR__ . "/Ripemd128Hasher.php");
require_once(__DIR__ . "/Ripemd160Hasher.php");
require_once(__DIR__ . "/Ripemd256Hasher.php");
require_once(__DIR__ . "/Ripemd320Hasher.php");
require_once(__DIR__ . "/Tiger128_3Hasher.php");
require_once(__DIR__ . "/Tiger160_3Hasher.php");
require_once(__DIR__ . "/Tiger192_3Hasher.php");

use ValueObject\Transformer\HashType;

class HasherFactory
{
    #region FACTORY
    public static function create(HashType $type): AbstractHasher
    {
        return match ($type) {
            HashType::SHA256      => new Sha256Hasher(),
            HashType::SHA384      => new Sha384Hasher(),
            HashType::SHA512      => new Sha512Hasher(),
            HashType::SHA3_224    => new Sha3_224Hasher(),
            HashType::SHA3_256    => new Sha3_256Hasher(),
            HashType::SHA3_384    => new Sha3_384Hasher(),
            HashType::SHA3_512    => new Sha3_512Hasher(),
            HashType::BLAKE2B     => new Blake2bHasher(),
            HashType::BLAKE2S     => new Blake2sHasher(),
            HashType::MD5         => new Md5Hasher(),
            HashType::SHA1        => new Sha1Hasher(),
            HashType::CRC32       => new Crc32Hasher(),
            HashType::CRC32B      => new Crc32bHasher(),
            HashType::WHIRLPOOL   => new WhirlpoolHasher(),
            HashType::RIPEMD128   => new Ripemd128Hasher(),
            HashType::RIPEMD160   => new Ripemd160Hasher(),
            HashType::RIPEMD256   => new Ripemd256Hasher(),
            HashType::RIPEMD320   => new Ripemd320Hasher(),
            HashType::TIGER128_3  => new Tiger128_3Hasher(),
            HashType::TIGER160_3  => new Tiger160_3Hasher(),
            HashType::TIGER192_3  => new Tiger192_3Hasher(),
        };
    }
    #endregion
}
