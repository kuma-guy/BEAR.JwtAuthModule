<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Encoder;

interface JwtEncoderInterface
{
    public function encode(array $payload) : string;

    public function decode(string $token) : array;
}
