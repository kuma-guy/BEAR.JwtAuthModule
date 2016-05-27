<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Encoder;

use BEAR\JwtAuthentication\Annotation\Algo;
use BEAR\JwtAuthentication\Annotation\Secret;
use BEAR\JwtAuthentication\Exception\InvalidTokenException;
use BEAR\JwtAuthentication\Exception\JwtException;
use Namshi\JOSE\JWS;

class NamshiSymmetric implements JwtEncoderInterface
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
    private $secret;

    /**
     * @Algo("algo")
     * @Secret("secret")
     */
    public function __construct(string $algo, string $secret)
    {
        $this->jws = new JWS(['typ' => 'JWT', 'alg' => $algo]);
        $this->algo = $algo;
        $this->secret = $secret;
    }

    public function encode(array $payload) : string
    {
        try {
            $this->jws->setPayload($payload)->sign($this->secret);

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

        if (!$jws->verify($this->secret, $this->algo)) {
            throw new InvalidTokenException('Invalid Token');
        }

        return (array) $jws->getPayload();
    }
}
