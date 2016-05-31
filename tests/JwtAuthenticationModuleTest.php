<?php

namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiAsymmetric;
use BEAR\JwtAuth\Encoder\NamshiSymmetric;
use BEAR\JwtAuth\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuth\Extractor\CookieTokenExtractor;
use BEAR\JwtAuth\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuth\Extractor\TokenExtractorInterface;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
use Ray\Di\Injector;

class JwtAuthModuleTest extends \PHPUnit_Framework_TestCase
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

        $this->assertInstanceOf(AuthorizationHeaderTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class));
        $this->assertInstanceOf(CookieTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'cookie'));
        $this->assertInstanceOf(QueryParameterTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'query'));

        $jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
        $this->assertInstanceOf(NamshiAsymmetric::class, $jwtEncoder);
    }

    public function testSymmetricModule()
    {
        $injector = (new Injector(new SymmetricJwtAuthModule('HS256', 86400, 'example_secret')));

        $jwtGenerator = $injector->getInstance(JwtGeneratorInterface::class);
        $this->assertInstanceOf(JwtGenerator::class, $jwtGenerator);

        $this->assertInstanceOf(AuthorizationHeaderTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class));
        $this->assertInstanceOf(CookieTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'cookie'));
        $this->assertInstanceOf(QueryParameterTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'query'));

        $jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
        $this->assertInstanceOf(NamshiSymmetric::class, $jwtEncoder);
    }
}
