<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Encoder;

trait JwtEncoderInject
{
    /**
     * @var JwtEncoderInterface
     */
    protected $jwtEncoder;

    /**
     * @param JwtEncoderInterface $jwtEncoder
     *
     * @\Ray\Di\Di\Inject
     */
    public function setJwtEncoder(JwtEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }
}
