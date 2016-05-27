<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Extractor;

use Aura\Web\Request;
use BEAR\JwtAuthentication\Annotation\QueryParam;

class QueryParameterTokenExtractor implements TokenExtractorInterface
{
    /**
     * @var string
     */
    private $queryParam;

    /**
     * @var Request
     */
    private $request;

    /**
     * @QueryParam("queryParam")
     */
    public function __construct(string $queryParam, Request $request)
    {
        $this->queryParam = $queryParam;
        $this->request = $request;
    }

    public function extract() : string
    {
        return $this->request->query->get($this->queryParam) ?? '';
    }
}
