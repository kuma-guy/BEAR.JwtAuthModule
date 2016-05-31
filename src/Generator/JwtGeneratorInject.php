<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Generator;

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
