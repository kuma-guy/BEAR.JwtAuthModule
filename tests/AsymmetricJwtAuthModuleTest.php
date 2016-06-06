<?php

namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiAsymmetric;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
use Ray\Di\Injector;

class AsymmetricJwtAuthModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAsymmetricModule()
    {
        $key = [
            'private' => file_get_contents(__DIR__ . '/Encoder/private-key.pem'),
            'public' => file_get_contents(__DIR__ . '/Encoder/public-key.pem'),
            'passphrase' => 'sample_passphrase'
        ];
        $injector = (new Injector(new AsymmetricJwtAuthModule('RS256', 86400, $key)));

        $jwtGenerator = $injector->getInstance(JwtGeneratorInterface::class);
        $this->assertInstanceOf(JwtGenerator::class, $jwtGenerator);

        $jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
        $this->assertInstanceOf(NamshiAsymmetric::class, $jwtEncoder);
    }
}
