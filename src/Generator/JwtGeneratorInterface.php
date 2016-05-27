<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Generator;

interface JwtGeneratorInterface
{
    public function create(array $payload) : string;
}
