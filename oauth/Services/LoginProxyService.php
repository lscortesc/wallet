<?php

namespace Oauth\Services;

use App\Wallet;
use App\Customer;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Oauth\Formatter\JsonFormatter;
use Illuminate\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Oauth\Exceptions\InvalidCredentialsException;

/**
 * Class LoginProxyService
 * @package Oauth\Services
 */
class LoginProxyService
{
    /**
     * @var AuthManager
     */
    private $auth;

    /**
     * @var DatabaseManager
     */
    private $database;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Request
     */
    private $request;


    /**
     * LoginProxyService constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->auth = $app->make('auth');
        $this->client = new Client([
            'base_uri' => env('APP_URL')
        ]);
        $this->request = $app->make('request');
        $this->database = $app->make('db');
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password)
    {
        $customer = Customer::where('email', $email)->first();

        if (! $customer) {
            throw new InvalidCredentialsException();
        }

        return $this->requestToken('password', [
            'username' => $email,
            'password' => $password
        ]);
    }

    /**
     * @param string $refreshToken
     * @return array
     */
    public function refresh(string $refreshToken): array
    {
        return $this->requestToken('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * @return array
     */
    public function logout(): array
    {
        $token = $this->auth->user()->token();
        $accessToken = str_replace(
            'Bearer ',
            '',
            $this->request->header('Authorization')
        );

        $this->database
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update([
                'revoked' => true
            ]);

        $token->revoke();

        return [
            'token' => $accessToken,
            'revoked' => true
        ];
    }

    /**
     * @param string $grantType
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function requestToken(string $grantType, array $data = []): array
    {
        $data = array_merge($data, [
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type' => $grantType
        ]);

        $response = $this->client->request('POST', 'oauth/token', [
            'json' => $data,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Format' => 'json'
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidCredentialsException();
        }

        $formatter = new JsonFormatter();
        $data = $formatter->decode($response->getBody());

        return [
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in,
            'refresh_token' => $data->refresh_token
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        // Create Customer
        $customer = new Customer;
        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->password = bcrypt($data['password']);
        $customer->save();

        // Create Wallet
        $wallet = new Wallet;
        $wallet->amount = 0;
        $wallet->currency_id = 'MXN';

        $customer->wallet()->save($wallet);

        return $customer->toArray();
    }
}
