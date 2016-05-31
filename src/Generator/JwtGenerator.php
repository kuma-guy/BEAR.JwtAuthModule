<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Generator;

use BEAR\JwtAuth\Annotation\Ttl;
use BEAR\JwtAuth\Encoder\JwtEncoderInject;

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
