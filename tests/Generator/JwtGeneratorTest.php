<?php

namespace BEAR\JwtAuthentication\Generator;

use BEAR\JwtAuthentication\Encoder\JwtEncoderInterface;
use BEAR\JwtAuthentication\SymmetricJwtAuthenticationModule;
use Ray\Di\Injector;

class JwtGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JwtGeneratorInterface
     */
    private $jwtGenerator;

    /**
     * @var JwtEncoderInterface
     */
    private $jwtEncoder;

    protected function setUp()
    {
        parent::setUp();
        $injector = (new Injector(new SymmetricJwtAuthenticationModule('HS256', 86400, 'example_secret')));
        $this->jwtGenerator = $injector->getInstance(JwtGeneratorInterface::class);
        $this->jwtEncoder = $injector->getInstance(JwtEncoderInterface::class);
    }

    /**
     * @test
     */
    public function shouldGenerateToken()
    {
        $payload = [
            'userid' => 1,
            'username' => 'admin'
        ];
        $token = $this->jwtGenerator->create($payload);
        $parts = explode('.', $token);
        $this->assertSame(3, count($parts));

        return $token;
    }

    /**
     * @test
     * @depends shouldGenerateToken
     */
    public function shouldBeValidToken($token)
    {
        $payload = $this->jwtEncoder->decode($token);
        $this->assertArrayHasKey('userid', $payload);
        $this->assertArrayHasKey('username', $payload);
        $this->assertArrayHasKey('exp', $payload);
        $this->assertArrayHasKey('iat', $payload);

        $this->assertSame(1, $payload['userid']);
        $this->assertSame('admin', $payload['username']);
    }
}
