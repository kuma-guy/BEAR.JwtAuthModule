<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Annotation\Algo;
use BEAR\JwtAuth\Annotation\Keys;
use BEAR\JwtAuth\Annotation\Ttl;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\Auth\AuthProvider;
use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiAsymmetric;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
use Ray\AuraWebModule\AuraWebModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AsymmetricJwtAuthModule extends AbstractModule
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
     * @var array
     */
    private $keys;

    /**
     * @param string $algo   Hashing algorithm
     * @param int    $ttl    Time to live
     * @param array  $keys   Used for asymmetric algorithms
     */
    public function __construct(string $algo, int $ttl, array $keys = [])
    {
        $this->algo = $algo;
        $this->ttl = $ttl;
        $this->keys = $keys;
    }

    protected function configure()
    {
        $this->install(new AuraWebModule());
        $this->install(new TokenExtractorModule());

        $this->bind()->annotatedWith(Algo::class)->toInstance($this->algo);
        $this->bind()->annotatedWith(Ttl::class)->toInstance($this->ttl);
        $this->bind()->annotatedWith(Keys::class)->toInstance($this->keys);

        $this->bind(JwtGeneratorInterface::class)->to(JwtGenerator::class)->in(Scope::SINGLETON);
        $this->bind(JwtEncoderInterface::class)->to(NamshiAsymmetric::class)->in(Scope::SINGLETON);
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
