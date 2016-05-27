<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class Ttl
{
    public $value;
}
