<?php

namespace BEAR\JwtAuthentication\Extractor;

use BEAR\JwtAuthentication\JwtAuthenticationModule;
use Ray\Di\Injector;

class AuthorizationHeaderTokenExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtractToken()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer example_token';
        $tokenExtractor = (new Injector(new JwtAuthenticationModule('HS256', 86400, 'example_secret')))->getInstance(TokenExtractorInterface::class);

        $token = $tokenExtractor->extract();
        $this->assertSame('example_token', $token);
    }

    /**
     * @test
     */
    public function shouldReturnNullCharacter()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bear example_token';
        $tokenExtractor = (new Injector(new JwtAuthenticationModule('HS256', 86400, 'example_secret')))->getInstance(TokenExtractorInterface::class);

        $token = $tokenExtractor->extract();
        $this->assertSame('', $token);
    }
}
