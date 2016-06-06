<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Encoder;

use BEAR\JwtAuth\Annotation\Algo;
use BEAR\JwtAuth\Annotation\PassPhrase;
use BEAR\JwtAuth\Annotation\PrivateKey;
use BEAR\JwtAuth\Annotation\PublicKey;
use BEAR\JwtAuth\Exception\InvalidTokenException;
use BEAR\JwtAuth\Exception\JwtException;
use Namshi\JOSE\JWS;

class NamshiAsymmetric implements JwtEncoderInterface
{
    /**
     * @var JWS
     */
    private $jws;

    /**
     * @var string
     */
    private $algo;

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
     * @Algo("algo")
     * @PrivateKey("privateKey")
     * @PublicKey("publicKey")
     * @PassPhrase("passPhrase")
     */
    public function __construct(string $algo, string $privateKey, string $publicKey, string $passPhrase)
    {
        $this->jws = new JWS(['typ' => 'JWT', 'alg' => $algo]);
        $this->algo = $algo;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->passPhrase = $passPhrase;
    }

    public function encode(array $payload) : string
    {
        try {
            $this->jws->setPayload($payload)->sign($this->privateKey, $this->passPhrase);

            return (string) $this->jws->getTokenString();
        } catch (Exception $e) {
            throw new JwtException($e->getMessage());
        }
    }

    public function decode(string $token) : array
    {
        try {
            $jws = $this->jws->load($token, false);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidTokenException($e->getMessage());
        }

        if (!$jws->verify($this->publicKey, $this->algo)) {
            throw new InvalidTokenException('Invalid Token');
        }

        return (array) $jws->getPayload();
    }
}
