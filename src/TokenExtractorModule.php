<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Annotation\Cookie;
use BEAR\JwtAuth\Annotation\Header;
use BEAR\JwtAuth\Annotation\QueryParam;
use BEAR\JwtAuth\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuth\Extractor\CookieTokenExtractor;
use BEAR\JwtAuth\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuth\Extractor\TokenExtractorInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class TokenExtractorModule extends AbstractModule
{
    /**
     * Used to detect token in header.
     *
     * @var array
     */
    private $header = ['Authorization', '/Bearer\s+(.*)$/i'];

    /**
     * Used to detect token in cookie.
     *
     * @var string
     */
    private $cookie = 'token';

    /**
     * Used to detect token in query string.
     *
     * @var string
     */
    private $queryParam = 'token';

    protected function configure()
    {
        $this->bind()->annotatedWith(Header::class)->toInstance($this->header);
        $this->bind()->annotatedWith(Cookie::class)->toInstance($this->cookie);
        $this->bind()->annotatedWith(QueryParam::class)->toInstance($this->queryParam);

        $this->bind(TokenExtractorInterface::class)->to(AuthorizationHeaderTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('query')->to(QueryParameterTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('cookie')->to(CookieTokenExtractor::class)->in(Scope::SINGLETON);
    }
}
