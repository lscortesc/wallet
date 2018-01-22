<?php

namespace Oauth\Factory;

use Exception;
use Illuminate\Http\Request;
use Oauth\Contracts\FormatterInterface;

/**
 * Class FormatterFactory
 * @package App\Http\Responses\Factory
 */
class FormatterFactory
{
    /**
     * @param Request $request
     * @return FormatterInterface
     * @throws Exception
     */
    public static function build(Request $request): FormatterInterface
    {
        $format = $request->hasHeader('x-format') ?
            ucwords(strtolower($request->header('x-format'))) : 'Json';

        $formatter = "Oauth\\Formatter\\{$format}Formatter";

        if (class_exists($formatter)) {
            return new $formatter();
        }

        throw new Exception('Formatter Class Not Found', 400);
    }
}
