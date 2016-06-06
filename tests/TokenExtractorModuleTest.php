<?php

namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuth\Extractor\CookieTokenExtractor;
use BEAR\JwtAuth\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuth\Extractor\TokenExtractorInterface;
use Ray\Di\Injector;

class TokenExtractorModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenExtractorModule()
    {
        $injector = (new Injector(new TokenExtractorModule()));

        $this->assertInstanceOf(AuthorizationHeaderTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class));
        $this->assertInstanceOf(CookieTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'cookie'));
        $this->assertInstanceOf(QueryParameterTokenExtractor::class, $injector->getInstance(TokenExtractorInterface::class, 'query'));
    }
}
