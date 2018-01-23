<?php

namespace Oauth\Http\Controllers;

use Oauth\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Oauth\Http\Requests\LoginRequest;
use Oauth\Services\LoginProxyService;
use Oauth\Contracts\FormatterInterface;
use Oauth\Http\Requests\RefreshRequest;
use Oauth\Http\Requests\RegisterRequest;

/**
 * Class ProxyController
 * @package Oauth\Http\Controllers
 */
class ProxyController extends Controller
{
    use ResponseTrait;

    /**
     * @var LoginProxyService
     */
    private $proxy;

    /**
     * ProxyController constructor.
     * @param LoginProxyService $proxy
     */
    public function __construct(LoginProxyService $proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @param LoginRequest $request
     * @return FormatterInterface
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        return $this->response(
            $this->proxy->login($email, $password)
        );
    }

    /**
     * @param RefreshRequest $request
     * @return FormatterInterface
     */
    public function refresh(RefreshRequest $request)
    {
        return $this->response(
            $this->proxy->refresh(
                $request->get('refresh_token')
            )
        );
    }

    /**
     * @return FormatterInterface
     */
    public function logout()
    {
        return $this->response($this->proxy->logout());
    }

    /**
     * @param RegisterRequest $request
     * @return FormatterInterface
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password'
        ]);

        return $this->response(
            $this->proxy->register($data),
            201
        );
    }
}
