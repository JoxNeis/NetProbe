<?php

namespace ValueObject\Transformer;

enum TransformType:string{
    case ENCODE = "ENCODE";
    case HASH = "HASH";
    case ENCRYPT = "ENCRYPT";
}