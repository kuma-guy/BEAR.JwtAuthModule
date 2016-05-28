<?php

namespace BEAR\JwtAuthentication;

use BEAR\JwtAuthentication\Encoder\JwtEncoderInterface;
use BEAR\JwtAuthentication\Encoder\NamshiAsymmetric;
use BEAR\JwtAuthentication\Encoder\NamshiSymmetric;
use BEAR\JwtAuthentication\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuthentication\Extractor\CookieTokenExtractor;
use BEAR\JwtAuthentication\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuthentication\Extractor\TokenExtractorInterface;
use BEAR\JwtAuthentication\Generator\JwtGenerator;
use BEAR\JwtAuthentication\Generator\JwtGeneratorInterface;
use Ray\Di\Injector;

class JwtAuthenticationModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAsymmetricModule()
    {
        $key = [
            'private' => file_get_contents(__DIR__ . '/Encoder/private-key.pem'),
            'public' => file_get_contents(__DIR__ . '/Encoder/public-key.pem'),
            'passphrase' => 'sample_passphrase'
        ];
        $injector = (new Injector(new AsymmetricJwtAuthenticationModule('RS256', 86400, $key)));

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
        $injector = (new Injector(new SymmetricJwtAuthenticationModule('HS256', 86400, 'example_secret')));

        $jwtGenerator = $injector->getInstance(JwtGeneratorInterface::class);
        $this->assertInstanceOf(JwtGenerator::class, $jwtGenerator);

        $this->assertInstanceOf(AuthorizationHeaderTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class));
        $this->assertInstanceOf(CookieTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'cookie'));
        $this->assertInstanceOf(QueryParameterTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'query'));

        $jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
        $this->assertInstanceOf(NamshiSymmetric::class, $jwtEncoder);
    }
}
