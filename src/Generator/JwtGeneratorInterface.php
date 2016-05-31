<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Generator;

interface JwtGeneratorInterface
{
    public function create(array $payload) : string;
}
