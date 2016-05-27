<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication;

use BEAR\JwtAuthentication\Annotation\Algo;
use BEAR\JwtAuthentication\Annotation\Cookie;
use BEAR\JwtAuthentication\Annotation\Header;
use BEAR\JwtAuthentication\Annotation\Keys;
use BEAR\JwtAuthentication\Annotation\QueryParam;
use BEAR\JwtAuthentication\Annotation\Secret;
use BEAR\JwtAuthentication\Annotation\Ttl;
use BEAR\JwtAuthentication\Auth\Auth;
use BEAR\JwtAuthentication\Auth\AuthProvider;
use BEAR\JwtAuthentication\Encoder\JwtEncoderInterface;
use BEAR\JwtAuthentication\Encoder\NamshiAsymmetric;
use BEAR\JwtAuthentication\Encoder\NamshiSymmetric;
use BEAR\JwtAuthentication\Extractor\AuthorizationHeaderTokenExtractor;
use BEAR\JwtAuthentication\Extractor\CookieTokenExtractor;
use BEAR\JwtAuthentication\Extractor\QueryParameterTokenExtractor;
use BEAR\JwtAuthentication\Extractor\TokenExtractorInterface;
use BEAR\JwtAuthentication\Generator\JwtGenerator;
use BEAR\JwtAuthentication\Generator\JwtGeneratorInterface;
use Ray\AuraWebModule\AuraWebModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class JwtAuthenticationModule extends AbstractModule
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
     * @var array
     */
    private $keys;

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
     * @param array  $keys   Used for asymmetric algorithms
     */
    public function __construct(string $algo, int $ttl, string $secret = '', array $keys = [])
    {
        $this->algo = $algo;
        $this->ttl = $ttl;
        $this->secret = $secret;
        $this->keys = $keys;
    }

    protected function configure()
    {
        $this->install(new AuraWebModule());

        $this->bind()->annotatedWith(Algo::class)->toInstance($this->algo);
        $this->bind()->annotatedWith(Ttl::class)->toInstance($this->ttl);
        $this->bind()->annotatedWith(Secret::class)->toInstance($this->secret);
        $this->bind()->annotatedWith(Keys::class)->toInstance($this->keys);

        $this->bind()->annotatedWith(Header::class)->toInstance($this->header);
        $this->bind()->annotatedWith(Cookie::class)->toInstance($this->cookie);
        $this->bind()->annotatedWith(QueryParam::class)->toInstance($this->queryParam);

        $this->bind(JwtGeneratorInterface::class)->to(JwtGenerator::class)->in(Scope::SINGLETON);

        $this->bind(TokenExtractorInterface::class)->to(AuthorizationHeaderTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('query')->to(QueryParameterTokenExtractor::class)->in(Scope::SINGLETON);
        $this->bind(TokenExtractorInterface::class)->annotatedWith('cookie')->to(CookieTokenExtractor::class)->in(Scope::SINGLETON);

        $this->configureEncoderByAlgorithm();

        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }

    private function configureEncoderByAlgorithm()
    {
        if ($this->secret) {
            $this->bind(JwtEncoderInterface::class)->to(NamshiSymmetric::class)->in(Scope::SINGLETON);

            return;
        }
        $this->bind(JwtEncoderInterface::class)->to(NamshiAsymmetric::class)->in(Scope::SINGLETON);
    }
}
