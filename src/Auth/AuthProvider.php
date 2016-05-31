<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Auth;

use BEAR\JwtAuth\Encoder\JwtEncoderInterface;
use BEAR\JwtAuth\Exception\InvalidTokenException;
use BEAR\JwtAuth\Extractor\TokenExtractorInterface;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class AuthProvider implements ProviderInterface
{
    /**
     * @var TokenExtractorInterface
     */
    private $tokenExtractor;

    /**
     * @var JwtEncoderInterface
     */
    private $jwtEncoder;

    public function __construct(TokenExtractorInterface $tokenExtractor, JwtEncoderInterface $jwtEncoder)
    {
        $this->tokenExtractor = $tokenExtractor;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @return Auth
     */
    public function get()
    {
        $auth = new Auth();
        $token = $this->tokenExtractor->extract();
        if (!$token) {
            return $auth;
        }

        try {
            $data = $this->jwtEncoder->decode($token);
        } catch (InvalidTokenException $e) {
            $auth->isLogin = false;

            return $auth;
        }

        $auth->userid = $data['userid'];
        $auth->username = $data['username'];
        $auth->isLogin = true;

        return $auth;
    }
}
