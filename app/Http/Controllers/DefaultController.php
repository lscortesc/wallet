<?php

namespace App\Http\Controllers;

use Oauth\Traits\ResponseTrait;

/**
 * Class DefaultController
 * @package App\Http\Controllers
 */
class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->response([
            'message' => 'Welcome to Larawallet. Conekta Test',
            'github' => 'https://github.com/lscortesc/wallet',
            'created_by' => '@lscortesc',
            'visit' => 'https://larawallet.herokuapp.com'
        ]);
    }
}
