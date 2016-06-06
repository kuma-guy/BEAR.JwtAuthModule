<?php

namespace BEAR\JwtAuth\Extractor;

use BEAR\JwtAuth\TokenExtractorModule;
use Ray\Di\Injector;

class QueryParameterTokenExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtractToken()
    {
        $_GET['token'] = 'example_token';
        $tokenExtractor = (new Injector(new TokenExtractorModule()))->getInstance(TokenExtractorInterface::class, 'query');

        $token = $tokenExtractor->extract();
        $this->assertSame('example_token', $token);
    }

    /**
     * @test
     */
    public function shouldReturnNullCharacter()
    {
        $_GET['invalid_key'] = 'example_token';
        $tokenExtractor = (new Injector(new TokenExtractorModule()))->getInstance(TokenExtractorInterface::class, 'query');

        $token = $tokenExtractor->extract();
        $this->assertSame('', $token);
    }
}
