<?php

declare(strict_types=1);

namespace App\Service;

use Illuminate\Http\Response;

class JsonResponse extends Response
{
    /**
     * @param mixed $data
     * @param int $status
     */
    public function __construct($data, int $status = 200)
    {
        parent::__construct(
            json_encode($data, JSON_THROW_ON_ERROR),
            $status,
            ['Content-Type' => 'application/json']
        );
    }
}
