<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Extractor;

interface TokenExtractorInterface
{
    public function extract() : string;
}
