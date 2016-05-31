<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Encoder;

interface JwtEncoderInterface
{
    public function encode(array $payload) : string;

    public function decode(string $token) : array;
}
