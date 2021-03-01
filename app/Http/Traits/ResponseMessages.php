<?php
declare(strict_types=1);

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ResponseMessages
{
    public function success(): array
    {
        return [
            'success'  => true,
            'status'   => Response::HTTP_CREATED,
            'messages' => 'Registration success',
        ];
    }

    public function error(): array
    {
        return [
            'success'  => false,
            'status'   => Response::HTTP_UNPROCESSABLE_ENTITY,
            'messages' => 'Registration error',
        ];
    }
}
