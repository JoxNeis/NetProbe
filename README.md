# NetProbe

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![cURL](https://img.shields.io/badge/cURL-Transport-073551?style=for-the-badge&logo=curl&logoColor=white)](https://curl.se/)
[![OpenSSL](https://img.shields.io/badge/OpenSSL-Encryption-721412?style=for-the-badge&logo=openssl&logoColor=white)](https://www.openssl.org/)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-F97316?style=for-the-badge)](CONTRIBUTING.md)

A clean, object-oriented PHP library for building and executing HTTP requests. Designed around separation of concerns — domain objects stay pure, infrastructure (cURL) stays isolated, and a builder layer wires them together.

---

## Architecture
```
┌──────────────────────────────────────────────────────────────┐
│                        Your Code                             │
└───────────────────────────┬──────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  HttpProject  (Domain Object)                                │
│  ─────────────────────────────────────────────────────────   │
│  name · description                                          │
│  Manage array of tasks                                       │
└───────────────────────────┬──────────────────────────────────┘
                            │
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  HttpTask  (Domain Object)                                   │
│  ─────────────────────────────────────────────────────────   │
│  name · address · method · headers · queries · body          │
│  Parameters carry optional transform pipelines               │
└───────────────────────────┬──────────────────────────────────┘
                            │  HttpRequestBuilder::build()
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  HttpRequest  (Value Object)                                 │
│  ─────────────────────────────────────────────────────────   │
│  Flat, serialized · header array · query string · body       │
└───────────────────────────┬──────────────────────────────────┘
                            │  HttpRequestRunner::run()
                            ▼
┌──────────────────────────────────────────────────────────────┐
│  HttpResponse                                                │
│  ─────────────────────────────────────────────────────────   │
│  statusCode · headers · body · curl_getinfo() data           │
└──────────────────────────────────────────────────────────────┘
```


| Layer | Class | Responsibility |
|---|---|---|
| Domain | `HttpTask` | Holds URL, method, headers, queries, body |
| Assembly | `HttpRequestBuilder` | Converts `HttpTask` into a flat `HttpRequest` |
| Transport | `HttpRequest` | Immutable data object passed to cURL |
| Execution | `HttpRequestRunner` | Executes via cURL, returns `HttpResponse` |

---

## Directory Structure

```
src/
├── Domain/
│   ├── Parameter/
│   │   ├── Parameter.php
│   │   └── HttpHolder/
│   │       ├── HttpParameterHolder.php
│   │       ├── HttpHeaderHolder.php
│   │       ├── HttpQueryHolder.php
│   │       └── HttpBodyHolder.php
│   ├── Project/
│   │   └── Project.php
│   ├── Request/
│   │   ├── HttpRequest.php
│   │   ├── Builder/
│   │   │   └── HttpRequestBuilder.php
│   │   └── Runner/
│   │       └── HttpRequestRunner.php
│   ├── Response/
│   │   └── HttpResponse.php
│   ├── Task/
│   │   ├── Task.php
│   │   └── HttpTask.php
│   ├── Transformer/
│   │   ├── Transformer.php
│   │   ├── TransformStep.php
│   │   ├── Encoder/
│   │   │   ├── AbstractEncoder.php
│   │   │   ├── EncoderFactory.php
│   │   │   ├── Base64Encoder.php
│   │   │   ├── Base64UrlEncoder.php
│   │   │   ├── HexEncoder.php
│   │   │   ├── HtmlSpecialEncoder.php
│   │   │   ├── Iso88591Encoder.php
│   │   │   ├── QuotedPrintableEncoder.php
│   │   │   ├── RawUrlEncoder.php
│   │   │   ├── Rot13Encoder.php
│   │   │   ├── UrlEncoder.php
│   │   │   ├── Utf8Encoder.php
│   │   │   ├── Utf16Encoder.php
│   │   │   ├── Utf32Encoder.php
│   │   │   └── UuencodeEncoder.php
│   │   ├── Hasher/
│   │   │   ├── AbstractHasher.php
│   │   │   ├── HasherFactory.php
│   │   │   ├── Blake2bHasher.php
│   │   │   ├── Blake2sHasher.php
│   │   │   ├── Crc32Hasher.php
│   │   │   ├── Crc32bHasher.php
│   │   │   ├── Md5Hasher.php
│   │   │   ├── Ripemd128Hasher.php
│   │   │   ├── Ripemd160Hasher.php
│   │   │   ├── Ripemd256Hasher.php
│   │   │   ├── Ripemd320Hasher.php
│   │   │   ├── Sha1Hasher.php
│   │   │   ├── Sha256Hasher.php
│   │   │   ├── Sha384Hasher.php
│   │   │   ├── Sha512Hasher.php
│   │   │   ├── Sha3_224Hasher.php
│   │   │   ├── Sha3_256Hasher.php
│   │   │   ├── Sha3_384Hasher.php
│   │   │   ├── Sha3_512Hasher.php
│   │   │   ├── Tiger128_3Hasher.php
│   │   │   ├── Tiger160_3Hasher.php
│   │   │   ├── Tiger192_3Hasher.php
│   │   │   └── WhirlpoolHasher.php
│   │   └── Encryptor/
│   │       ├── AbstractEncryptor.php
│   │       ├── Encryptor.php
│   │       ├── EncryptorFactory.php
│   │       ├── Aes128CbcEncryptor.php
│   │       ├── Aes192CbcEncryptor.php
│   │       ├── Aes256CbcEncryptor.php
│   │       ├── Aes128GcmEncryptor.php
│   │       ├── Aes192GcmEncryptor.php
│   │       ├── Aes256GcmEncryptor.php
│   │       ├── BfCbcEncryptor.php
│   │       ├── Camellia128CbcEncryptor.php
│   │       ├── Camellia192CbcEncryptor.php
│   │       ├── Camellia256CbcEncryptor.php
│   │       ├── ChaCha20Poly1305Encryptor.php
│   │       ├── DesCbcEncryptor.php
│   │       └── DesEde3CbcEncryptor.php
│   └── ValueObject/
│       ├── DataType.php
│       ├── HttpHeaderCategory.php
│       ├── HttpRequestMethod.php
│       └── Http/
│           ├── HttpBodyType.php
│           ├── HttpHeaderCategory.php
│           ├── HttpRequestMethod.php
│           └── Transformer/
│               ├── EncodeType.php
│               ├── EncryptType.php
│               └── HashType.php
```

## Core Concepts

### HttpTask

The domain object. Holds all the data needed to describe an HTTP request without knowing anything about cURL.

```php
$task = new HttpTask(
    name: 'Get User',
    address: 'https://api.example.com/users/1',
    description: 'Fetch a single user by ID',
    method: HttpRequestMethod::GET
);

// Add a header
$task->addHeader(new Parameter(
    HttpHeaderCategory::AUTHORIZATION,
    'Bearer my-token',
    DataType::TEXT
));

// Add a query parameter
$task->addQuery(new Parameter('page', '2', DataType::INTEGER));
```

### HttpRequestBuilder

Converts an `HttpTask` into a flat `HttpRequest` ready for execution. Handles header formatting, query string assembly, and body serialization based on `Content-Type`.

```php
$builder = new HttpRequestBuilder();
$request = $builder->build($task);
```

Body serialization is driven by the `Content-Type` header:

| Content-Type | Output |
|---|---|
| `application/json` | `json_encode($data)` |
| `application/x-www-form-urlencoded` | `http_build_query($data)` |
| `multipart/form-data` | Array (cURL handles encoding) |

### HttpRequestRunner

Executes an `HttpRequest` via cURL and returns an `HttpResponse`. Fluent configuration API:

```php
$runner = (new HttpRequestRunner())
    ->withTimeout(30)
    ->withConnectTimeout(10)
    ->withSslVerification(true)
    ->withFollowRedirects(true, 5);

$response = $runner->run($request);

echo $response->getHttpStatusCode(); // 200
echo $response->getBody();           // Raw response body
print_r($response->getHeader());     // Parsed response headers
print_r($response->getInfo());       // curl_getinfo() data
```

### Parameter & TransformSteps

A `Parameter` holds a key-value pair and an optional pipeline of transforms applied when `getModifiedValue()` is called. Transforms are applied in order: encode → hash → encrypt.

```php
$param = new Parameter(
    key: 'password',
    value: 'secret',
    type: DataType::TEXT,
    steps: [
        new TransformStep(HashType::SHA256),
        new TransformStep(EncodeType::BASE64),
    ]
);

$param->getModifiedValue(); // base64(sha256("secret"))
```

---

## Transformers

### Encoders (`EncodeType`)

| Case | Algorithm |
|---|---|
| `BASE64` | `base64_encode()` |
| `BASE64_URL` | RFC 4648 §5 URL-safe base64 |
| `HEX` | `bin2hex()` |
| `URL` | `urlencode()` |
| `RAW_URL` | `rawurlencode()` (RFC 3986) |
| `HTML_SPECIAL` | `htmlspecialchars()` |
| `QUOTED_PRINTABLE` | RFC 2045 |
| `UUENCODE` | Unix-to-Unix encoding |
| `ROT13` | ROT-13 substitution |
| `UTF8` / `UTF16` / `UTF32` | Character encoding conversion |
| `ISO_8859_1` | Latin-1 conversion |

### Hashers (`HashType`)

SHA-2 family (256, 384, 512), SHA-3 family (224, 256, 384, 512), BLAKE2b, BLAKE2s, MD5, SHA-1, CRC32, CRC32b, Whirlpool, RIPEMD (128, 160, 256, 320), Tiger (128, 160, 192).

### Encryptors (`EncryptType`)

| Case | Cipher |
|---|---|
| `AES_128/192/256_CBC` | AES-CBC (IV prepended, base64 output) |
| `AES_128/192/256_GCM` | AES-GCM (IV + tag prepended, base64 output) |
| `CHACHA20_POLY1305` | ChaCha20-Poly1305 AEAD |
| `DES_CBC` | DES-CBC |
| `DES_EDE3_CBC` | Triple DES |
| `BF_CBC` | Blowfish |
| `CAMELLIA_128/192/256_CBC` | Camellia |

GCM and ChaCha20 ciphers output in the format `base64(IV . tag . ciphertext)`. CBC ciphers output `base64(IV . ciphertext)`.

---

## Projects & Tasks

`Project` is a named container that groups multiple `Task` objects together.

```php
$project = new Project('My API Suite', 'Collection of API tasks');
$project->addTask($task);

$array = $project->toArray(); // Serializable snapshot
```

---

## Requirements

- PHP 8.1+ (enums, match expressions, named arguments)
- `ext-curl`
- `ext-openssl`
- `ext-mbstring`
