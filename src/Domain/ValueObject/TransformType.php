<?php

namespace ValueObject;

enum TransformType:string{
    case ENCODE = "ENCODE";
    case HASH = "HASH";
    case ENCRYPT = "ENCRYPT";
}