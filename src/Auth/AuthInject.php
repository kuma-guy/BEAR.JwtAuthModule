<?php

/**
 * This file is part of the BEAR.JwtAuthModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuth\Auth;

trait AuthInject
{
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @param Auth $auth
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
    }
}
