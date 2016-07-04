<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Annotation\Algo;
use BEAR\JwtAuth\Annotation\Secret;
use BEAR\JwtAuth\Annotation\Ttl;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\Auth\AuthProvider;
use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiSymmetric;
use BEAR\JwtAuth\Exception\NoConfigException;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
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
     * @param string $algo   Hashing algorithm
     * @param int    $ttl    Time to live
     * @param string $secret Used for symmetric algorithms
     */
    public function __construct(string $algo, int $ttl, string $secret)
    {
        if ($algo == ''
            || $ttl < 0
            || $secret == '') {
            throw new NoConfigException;
        }

        $this->algo = $algo;
        $this->ttl = $ttl;
        $this->secret = $secret;
    }

    protected function configure()
    {
        $this->install(new TokenExtractorModule());

        $this->bind()->annotatedWith(Algo::class)->toInstance($this->algo);
        $this->bind()->annotatedWith(Ttl::class)->toInstance($this->ttl);
        $this->bind()->annotatedWith(Secret::class)->toInstance($this->secret);

        $this->bind(JwtGeneratorInterface::class)->to(JwtGenerator::class)->in(Scope::SINGLETON);
        $this->bind(JwtEncoderInterface::class)->to(NamshiSymmetric::class)->in(Scope::SINGLETON);
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
