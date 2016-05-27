<?php

/**
 * This file is part of the BEAR.JwtAuthenticationModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\JwtAuthentication\Auth;

use Ray\Di\Di\Qualifier;

/**
 * @Qualifier
 */
final class Auth
{
    /**
     * @var string
     */
    public $userid;

    /**
     * @var string
     */
    public $username;

    /**
     * @var bool
     */
    public $isLogin;
}
