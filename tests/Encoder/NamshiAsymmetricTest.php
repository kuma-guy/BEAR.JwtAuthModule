<?php

namespace BEAR\JwtAuthentication\Encoder;

use BEAR\JwtAuthentication\Exception\InvalidTokenException;
use BEAR\JwtAuthentication\JwtAuthenticationModule;
use Ray\Di\Injector;

class NamshiAsymmetricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Namshi
     */
    private $jwtEncoder;

    protected function setUp()
    {
        parent::setUp();
        $key = [
            'private' => file_get_contents(__DIR__ . '/private-key.pem'),
            'public' => file_get_contents(__DIR__ . '/public-key.pem'),
            'passphrase' => 'sample_passphrase'
        ];
        $this->jwtEncoder = (new Injector(new JwtAuthenticationModule('RS256', 86400, '', $key)))->getInstance(JwtEncoderInterface::class);
    }

    /**
     * @test
     */
    public function shouldReturnValidToken()
    {
        $this->payload = ['userid' => 1, 'username' => 'admin', 'iat' => 1353601026, 'exp' => 1353604926];
        $token = $this->jwtEncoder->encode($this->payload);

        return $token;
    }

    /**
     * @test
     * @depends shouldReturnValidToken
     */
    public function shouldReturnValidPayload($token)
    {
        $payload = $this->jwtEncoder->decode($token);

        $this->assertSame(1, $payload['userid']);
        $this->assertSame('admin', $payload['username']);
        $this->assertSame(1353601026, $payload['iat']);
        $this->assertSame(1353604926, $payload['exp']);
    }

    /**
     * @test
     */
    public function shouldThrowInvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $token = 'invalid_token';
        $this->jwtEncoder->decode($token);
    }
}
