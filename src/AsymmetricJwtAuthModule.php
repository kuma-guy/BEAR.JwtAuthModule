<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth;

use BEAR\JwtAuth\Annotation\Algo;
use BEAR\JwtAuth\Annotation\PassPhrase;
use BEAR\JwtAuth\Annotation\PrivateKey;
use BEAR\JwtAuth\Annotation\PublicKey;
use BEAR\JwtAuth\Annotation\Ttl;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\Auth\AuthProvider;
use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Encoder\NamshiAsymmetric;
use BEAR\JwtAuth\Generator\JwtGenerator;
use BEAR\JwtAuth\Generator\JwtGeneratorInterface;
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
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $passPhrase;

    /**
     * @param string $algo       Hashing algorithm
     * @param int    $ttl        Time to live for JWT
     * @param string $privateKey Private key
     * @param string $publicKey  Public key
     * @param string $passPhrase Pass phrase
     */
    public function __construct(string $algo, int $ttl, string $privateKey, string $publicKey, string $passPhrase)
    {
        $this->algo = $algo;
        $this->ttl = $ttl;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->passPhrase = $passPhrase;
    }

    protected function configure()
    {
        $this->install(new TokenExtractorModule());

        $this->bind()->annotatedWith(Algo::class)->toInstance($this->algo);
        $this->bind()->annotatedWith(Ttl::class)->toInstance($this->ttl);
        $this->bind()->annotatedWith(PrivateKey::class)->toInstance($this->privateKey);
        $this->bind()->annotatedWith(PublicKey::class)->toInstance($this->publicKey);
        $this->bind()->annotatedWith(PassPhrase::class)->toInstance($this->passPhrase);

        $this->bind(JwtGeneratorInterface::class)->to(JwtGenerator::class)->in(Scope::SINGLETON);
        $this->bind(JwtEncoderInterface::class)->to(NamshiAsymmetric::class)->in(Scope::SINGLETON);
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
