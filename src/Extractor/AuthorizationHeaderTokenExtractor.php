<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Extractor;

use Aura\Web\Request;
use BEAR\JwtAuth\Annotation\Header;

class AuthorizationHeaderTokenExtractor implements TokenExtractorInterface
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $regexp;

    /**
     * @var Request
     */
    private $request;

    /**
     * @Header("header")
     */
    public function __construct(array $header, Request $request)
    {
        list($this->header, $this->regexp) = $header;
        $this->request = $request;
    }

    public function extract() : string
    {
        $token = $this->request->headers->get($this->header);

        if (preg_match($this->regexp, $token, $matches)) {
            return $matches[1];
        }

        return '';
    }
}
