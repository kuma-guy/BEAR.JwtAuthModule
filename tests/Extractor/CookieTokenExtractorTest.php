<?php

namespace BEAR\JwtAuth\Extractor;

use BEAR\JwtAuth\SymmetricJwtAuthModule;
use Ray\Di\Injector;

class CookieTokenExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtractToken()
    {
        $_COOKIE['token'] = 'example_token';
        $tokenExtractor = (new Injector(new SymmetricJwtAuthModule('HS256', 86400, 'example_secret')))->getInstance(TokenExtractorInterface::class, 'cookie');

        $token = $tokenExtractor->extract();
        $this->assertSame('example_token', $token);
    }

    /**
     * @test
     */
    public function shouldReturnNullCharacter()
    {
        $_COOKIE['invalid_key'] = 'example_token';
        $tokenExtractor = (new Injector(new SymmetricJwtAuthModule('HS256', 86400, 'example_secret')))->getInstance(TokenExtractorInterface::class, 'cookie');

        $token = $tokenExtractor->extract();
        $this->assertSame('', $token);
    }
}
