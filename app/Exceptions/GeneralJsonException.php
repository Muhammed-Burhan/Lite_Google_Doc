<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GeneralJsonException extends Exception
{
    protected $code = 422;

    public  function report()
    {
        $a = 'dada';
        dump($a);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render()
    {
        return new JsonResponse([
            'error' => 'Something went wrong.'
        ], $this->code);
    }
}