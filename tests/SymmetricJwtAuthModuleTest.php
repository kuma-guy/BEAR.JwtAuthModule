<?php

namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiSymmetric;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
use Ray\Di\Injector;

class SymmetricJwtAuthModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSymmetricModule()
    {
        $injector = (new Injector(new SymmetricJwtAuthModule('HS256', 86400, 'example_secret')));

        $jwtGenerator = $injector->getInstance(JwtGeneratorInterface::class);
        $this->assertInstanceOf(JwtGenerator::class, $jwtGenerator);

        $jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
        $this->assertInstanceOf(NamshiSymmetric::class, $jwtEncoder);
    }
}
