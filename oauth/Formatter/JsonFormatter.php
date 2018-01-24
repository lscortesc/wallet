<?php

namespace Oauth\Formatter;

use Exception;
use Oauth\Contracts\FormatterInterface;

/**
 * Class JsonFormatter
 * @package Oauth\Formatter
 */
class JsonFormatter implements FormatterInterface
{
    /**
     * @var array
     */
    protected static $messages = [
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error,
            possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly
            incorrectly encoded'
    ];

    /**
     * @param $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = [], int $status = 200)
    {
        return response()->json(
            [
                'data' => $data,
                'code' => $status,
                'message' => trans("messages.response.$status")
            ],
            $status
        );
    }

    /**
     * @param string $data
     * @return string
     * @throws Exception
     */
    public function decode(string $data)
    {
        try {
            $decoded = json_decode($data);
            $this->verify($decoded);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $decoded;
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     */
    private function verify($data): bool
    {
        if ($data === false) {
            throw new Exception(
                "Error ". $this->getError() . ": " . $this->getErrorMessage(),
                400
            );
        }

        return true;
    }

    /**
     * @return string
     */
    private function getErrorMessage(): string
    {
        return static::$messages[json_last_error()];
    }

    /**
     * @return int
     */
    private function getError(): int
    {
        return json_last_error();
    }
}
