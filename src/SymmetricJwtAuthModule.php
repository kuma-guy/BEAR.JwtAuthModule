<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Annotation\Algo;
use BEAR\JwtAuth\Annotation\Cookie;
use BEAR\JwtAuth\Annotation\Header;
use BEAR\JwtAuth\Annotation\QueryParam;
use BEAR\JwtAuth\Annotation\Secret;
use BEAR\JwtAuth\Annotation\Ttl;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\Auth\AuthProvider;
use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiSymmetric;
use BEAR\JwtAuth\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuth\Extractor\CookieTokenExtractor;
use BEAR\JwtAuth\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuth\Extractor\TokenExtractorInterface;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
use Ray\AuraWebModule\AuraWebModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class SymmetricJwtAuthModule extends AbstractModule
{
    /**
     * @var string
     */
    private $algo;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var string
     */
    private $secret;

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

    /**
     * @param string $algo   Hashing algorithm
     * @param int    $ttl    Time to live
     * @param string $secret Used for symmetric algorithms
     */
    public function __construct(string $algo, int $ttl, string $secret = '')
    {
        $this->algo = $algo;
        $this->ttl = $ttl;
        $this->secret = $secret;
    }

    protected function configure()
    {
        $this->install(new AuraWebModule());

        $this->bind()->annotatedWith(Algo::class)->toInstance($this->algo);
        $this->bind()->annotatedWith(Ttl::class)->toInstance($this->ttl);
        $this->bind()->annotatedWith(Secret::class)->toInstance($this->secret);
        $this->bind()->annotatedWith(Header::class)->toInstance($this->header);
        $this->bind()->annotatedWith(Cookie::class)->toInstance($this->cookie);
        $this->bind()->annotatedWith(QueryParam::class)->toInstance($this->queryParam);

        $this->bind(JwtGeneratorInterface::class)->to(JwtGenerator::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->to(AuthorizationHeaderTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('query')->to(QueryParameterTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('cookie')->to(CookieTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(JwtEncoderInterface::class)->to(NamshiSymmetric::class)->in(Scope::SINGLETON);
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
