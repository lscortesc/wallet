<?php

namespace Oauth\Traits;

/**
 * Class ResponseTrait
 * @package Oauth\Traits
 */
trait ResponseTrait
{
    /**
     * @param $data
     * @param $status
     * @return mixed
     */
    public function response($data, $status = 200)
    {
        return request()->formatter->response($data, $status);
    }
}
