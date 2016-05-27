<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Generator;

use BEAR\JwtAuthentication\Annotation\Ttl;
use BEAR\JwtAuthentication\Encoder\JwtEncoderInject;

class JwtGenerator implements JwtGeneratorInterface
{
    use JwtEncoderInject;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @Ttl("ttl")
     */
    public function __construct(string $ttl)
    {
        $this->ttl = $ttl;
    }

    public function create(array $payload) : string
    {
        $now = time();
        $payload['exp'] = $now + $this->ttl;
        $payload['iat'] = $now;

        $token = $this->jwtEncoder->encode($payload);

        return $token;
    }
}
