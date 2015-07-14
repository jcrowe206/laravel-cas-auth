<?php

namespace JCrowe\LaravelCasAuth;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class LaravelCasAuthGuardProvider extends Guard {

    /**
     * @var array
     */
    protected $defaults = [
        'sso_server_host' => 'http://localhost',
        'login_endpoint'  => '/login',
        'logout_endpoint' => '/logout',
        'requires_ssl'    => true
    ];


    /**
     * @var array
     */
    protected $options  = [];


    /**
     * The currently authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user = null;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

    /**
     * Indicates if the user was authenticated via a recaller cookie.
     *
     * @var bool
     */
    protected $viaRemember = false;

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $provider;

    /**
     * The session used by the guard.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * The Illuminate cookie creator service.
     *
     * @var \Illuminate\Contracts\Cookie\QueueingFactory
     */
    protected $cookie;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Indicates if a token user retrieval has been attempted.
     *
     * @var bool
     */
    protected $tokenRetrievalAttempted = false;


    /**
     * @param array $configs
     */
    public function __construct(
        UserProvider $provider,
        SessionInterface $session,
        Request $request = null)
    {
        $this->options = array_merge($this->defaults, config('laravel-cas', []));

        parent::__construct($provider, $session, $request);
    }


    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return null;
        }

        if (is_null($this->user) && \phpCAS::getUser()) {
            $this->user = \phpCAS::getUser();
        }

        return $this->user;
    }


    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  bool $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        \phpCAS::forceAuthentication();
    }


    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        \phpCAS::logout();
    }


}