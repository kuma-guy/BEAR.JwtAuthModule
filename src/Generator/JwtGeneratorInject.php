<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Generator;

trait JwtGeneratorInject
{
    /**
     * @var JwtGeneratorInterface
     */
    protected $jwtGenerator;

    /**
     * @param JwtGeneratorInterface $jwtGenerator
     *
     * @\Ray\Di\Di\Inject
     */
    public function setJwtGenerator(JwtGeneratorInterface $jwtGenerator)
    {
        $this->jwtGenerator = $jwtGenerator;
    }
}
