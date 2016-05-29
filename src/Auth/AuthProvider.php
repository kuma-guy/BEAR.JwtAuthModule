<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Auth;

use BEAR\JwtAuthentication\Encoder\JwtEncoderInterface;
use BEAR\JwtAuthentication\Exception\InvalidTokenException;
use BEAR\JwtAuthentication\Extractor\TokenExtractorInterface;
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
