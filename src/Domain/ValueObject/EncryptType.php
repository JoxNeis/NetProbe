<?php

namespace ValueObject;

enum EncryptType: string
{
    case NONE = 'none';
    case AES_128_CBC = 'aes-128-cbc';
    case AES_192_CBC = 'aes-192-cbc';
    case AES_256_CBC = 'aes-256-cbc';
    case AES_128_GCM = 'aes-128-gcm';
    case AES_192_GCM = 'aes-192-gcm';
    case AES_256_GCM = 'aes-256-gcm';
    case CHACHA20_POLY1305 = 'chacha20-poly1305';
    case DES_CBC = 'des-cbc';
    case DES_EDE3_CBC = 'des-ede3-cbc'; 
    case BF_CBC = 'bf-cbc'; 
    case CAMELLIA_128_CBC = 'camellia-128-cbc';
    case CAMELLIA_192_CBC = 'camellia-192-cbc';
    case CAMELLIA_256_CBC = 'camellia-256-cbc';
}