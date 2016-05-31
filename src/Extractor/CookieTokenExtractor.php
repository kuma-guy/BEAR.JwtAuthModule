<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Extractor;

use Aura\Web\Request;
use BEAR\JwtAuth\Annotation\Cookie;

class CookieTokenExtractor implements TokenExtractorInterface
{
    /**
     * @var string
     */
    private $cookie;

    /**
     * @var Request
     */
    private $request;

    /**
     * @Cookie("cookie")
     */
    public function __construct(string $cookie, Request $request)
    {
        $this->cookie = $cookie;
        $this->request = $request;
    }

    public function extract() : string
    {
        return $this->request->cookies->get($this->cookie) ?? '';
    }
}
