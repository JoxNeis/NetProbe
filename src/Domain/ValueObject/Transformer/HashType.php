<?php

namespace ValueObject\Transformer;

enum HashType: string
{
    case SHA256 = 'sha256';
    case SHA384 = 'sha384';
    case SHA512 = 'sha512';
    case SHA3_224 = 'sha3-224';
    case SHA3_256 = 'sha3-256';
    case SHA3_384 = 'sha3-384';
    case SHA3_512 = 'sha3-512';
    case BLAKE2B = 'blake2b';
    case BLAKE2S = 'blake2s';
    case MD5 = 'md5';
    case SHA1 = 'sha1';
    case CRC32 = 'crc32';
    case CRC32B = 'crc32b';
    case WHIRLPOOL = 'whirlpool';
    case RIPEMD128 = 'ripemd128';
    case RIPEMD160 = 'ripemd160';
    case RIPEMD256 = 'ripemd256';
    case RIPEMD320 = 'ripemd320';
    case TIGER128_3 = 'tiger128,3';
    case TIGER160_3 = 'tiger160,3';
    case TIGER192_3 = 'tiger192,3';
}