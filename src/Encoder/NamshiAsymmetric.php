<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Encoder;

use BEAR\JwtAuthentication\Annotation\Algo;
use BEAR\JwtAuthentication\Annotation\Keys;
use BEAR\JwtAuthentication\Exception\InvalidTokenException;
use BEAR\JwtAuthentication\Exception\JwtException;
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
     * @var array
     */
    private $keys;

    /**
     * @Algo("algo")
     * @Keys("keys")
     */
    public function __construct(string $algo, array $keys)
    {
        $this->jws = new JWS(['typ' => 'JWT', 'alg' => $algo]);
        $this->algo = $algo;
        $this->keys = $keys;
    }

    public function encode(array $payload) : string
    {
        try {
            $this->jws->setPayload($payload)->sign($this->keys['private'], $this->keys['passphrase']);

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

        if (!$jws->verify($this->keys['public'], $this->algo)) {
            throw new InvalidTokenException('Invalid Token');
        }

        return (array) $jws->getPayload();
    }
}
