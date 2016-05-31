<?php

namespace BEAR\JwtAuth\Encoder;

use BEAR\JwtAuth\Exception\InvalidTokenException;
use BEAR\JwtAuth\SymmetricJwtAuthModule;
use Ray\Di\Injector;

class NamshiSymmetricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JwtEncoderInterface
     */
    private $jwtEncoder;

    protected function setUp()
    {
        parent::setUp();
        $this->jwtEncoder = (new Injector(new SymmetricJwtAuthModule('HS256', 86400, 'example_secret')))->getInstance(JwtEncoderInterface::class);
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
