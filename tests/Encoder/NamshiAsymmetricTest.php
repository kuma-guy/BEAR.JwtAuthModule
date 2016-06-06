<?php

namespace BEAR\JwtAuth\Encoder;

use BEAR\JwtAuth\AsymmetricJwtAuthModule;
use BEAR\JwtAuth\Exception\InvalidTokenException;
use Ray\Di\Injector;

class NamshiAsymmetricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JwtEncoderInterface
     */
    private $jwtEncoder;

    protected function setUp()
    {
        parent::setUp();
        $privateKey = file_get_contents(__DIR__ . '/private-key.pem');
        $publicKey = file_get_contents(__DIR__ . '/public-key.pem');
        $passPhrase = 'sample_passphrase';
        $this->jwtEncoder = (new Injector(new AsymmetricJwtAuthModule('RS256', 86400, $privateKey, $publicKey, $passPhrase)))->getInstance(JwtEncoderInterface::class);
    }

    /**
     * @test
     */
    public function shouldReturnValidToken()
    {
        $payload = ['userid' => 1, 'username' => 'admin', 'iat' => 1353601026, 'exp' => 1353604926];
        $token = $this->jwtEncoder->encode($payload);

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
        $this->jwtEncoder->decode('invalid_token');
    }
}
